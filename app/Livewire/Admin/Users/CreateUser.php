<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Models\Area;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    use LivewireAlert;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|max:2')]
    public string $locale = 'es';

    /** @var array<int> */
    #[Validate('nullable|array')]
    public array $selectedRoles = [];

    // Áreas (solo múltiples)
    /** @var array<int> */
    #[Validate(['nullable','array','distinct','exists:areas,id'])]
    public array $areaIds = [];

    // Password
    public string $password = '';
    public string $password_confirmation = '';
    public bool   $passwordVisible = false;
    public bool   $ConfirmationPasswordVisible = false;

    public function mount(): void
    {
        $this->authorize('create users');
    }

    public function selectAllAreas(): void
    {
        $this->areaIds = Area::pluck('id')->map(fn($id) => (int)$id)->all();
    }

    public function clearAreas(): void
    {
        $this->areaIds = [];
    }

    public function createUser(): void
    {
        // 1) Valida los campos con atributos
        $this->validate();

        // 2) Valida password + confirmación con reglas por defecto de Laravel
        $this->validate([
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // 3) Crea el usuario con la contraseña ingresada (no aleatoria)
        $user = User::query()->create([
            'name'   => $this->name,
            'email'  => $this->email,
            'password' => Hash::make($this->password),
            'locale' => $this->locale,
        ]);

        // 4) Roles (si aplica)
        if ($this->selectedRoles !== []) {
            $userRoles = Arr::map($this->selectedRoles, fn ($role): int => (int) $role);
            $user->syncRoles($userRoles);
        }

        // Áreas (pivot)
        $user->areas()->sync($this->areaIds ?? []);

        $this->flash('success', __('users.user_created'));
        $this->redirect(route('admin.users.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.users.create-user', [
            'roles'   => Role::all(),
            'areas'   => Area::orderBy('nombre')->get(),
            'locales' => [
                'es' => 'Español',
                'en' => 'English',
                'da' => 'Danish',
            ],
        ]);
    }
}
