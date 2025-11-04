<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Models\Area;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;
use Spatie\Permission\Models\Role;

class EditUser extends Component
{
    use HandlesRedirects, LivewireAlert;

    public User $user;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';

    public string $email = '';

    #[Validate('required|string|max:2')]
    public string $locale = 'es';

    /** @var array<int,string> */
    public array $userRoles = [];

    /** Áreas (checklist) */
    /** @var array<int> */
    #[Validate(['nullable','array','distinct','exists:areas,id'])]
    public array $areaIds = [];

    public string $new_password = '';
    public string $new_password_confirmation = '';
    public bool $passwordVisible = false;
    public bool $confirmationPasswordVisible = false;

    public function mount(User $user): void
    {
        $this->authorize('update users');

        $this->user   = $user;
        $this->name   = $user->name;
        $this->email  = $user->email;
        $this->locale = $user->locale ?? 'es';
        $this->userRoles = $user->roles->pluck('id')->toArray();

        // Cargar áreas del usuario al checklist
        $this->areaIds = $user->areas()->pluck('areas.id')->map(fn($id) => (int)$id)->all();
    }

    public function selectAllAreas(): void
    {
        $this->areaIds = Area::pluck('id')->map(fn($id) => (int)$id)->all();
    }

    public function clearAreas(): void
    {
        $this->areaIds = [];
    }

    public function updateUser(): void
    {
        $this->authorize('update users');

        // Valida los atributos con #[Validate]
        $this->validate();

        // Email único (ignorando al usuario actual)
        $this->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
        ]);

        // Si el admin ingresó nueva contraseña, validar y aplicar
        if (filled($this->new_password)) {
            $this->validate([
                'new_password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);
        }

        $updates = [
            'name'   => $this->name,
            'email'  => $this->email,
            'locale' => $this->locale,
        ];

        if (filled($this->new_password)) {
            $updates['password'] = Hash::make($this->new_password);
        }

        $this->user->update($updates);

        // Roles
        $userRoles = Arr::map($this->userRoles, fn ($role): int => (int) $role);
        $this->user->syncRoles($userRoles);

        // Áreas (pivot)
        $this->user->areas()->sync(array_map('intval', $this->areaIds ?? []));

        $this->flash('success', __('users.user_updated'));
        $this->redirect(route('admin.users.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.users.edit-user', [
            'roles' => Role::all(),
            'areas' => Area::orderBy('nombre')->get(),
            'locales' => [
                'es' => 'Español',
                'en' => 'English',
                'da' => 'Danish',
            ],
        ]);
    }
}
