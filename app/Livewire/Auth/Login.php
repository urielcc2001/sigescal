<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Route;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public bool $passwordVisible = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        $creds = ['email' => $this->email, 'password' => $this->password];
        $ok = false;
        $guardUsed = 'web';

        // 1) Si es correo institucional, intenta primero como estudiante
        if (str_ends_with(strtolower($this->email), '@tuxtepec.tecnm.mx')) {
            if (Auth::guard('students')->attempt($creds, false)) {
                $ok = true;
                $guardUsed = 'students';
            }
        }

        // 2) Si no entró por students (o no era institucional), intenta como usuario interno
        if (! $ok && Auth::guard('web')->attempt($creds, false)) {
            $ok = true;
            $guardUsed = 'web';
        }

        if (! $ok) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        // Redirección según guard
        $route = ($guardUsed === 'students' && Route::has('students.dashboard'))
            ? route('students.dashboard', absolute: false)
            : route('dashboard', absolute: false);

        $this->redirectIntended(default: $route, navigate: true);

    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
