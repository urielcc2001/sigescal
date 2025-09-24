<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{ __('users.edit_user') }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ __('users.edit_user_description') }}
        </x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="updateUser" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('users.name') }}"/>

        <flux:input wire:model.live="email" label="{{ __('users.email') }}"/>

        <flux:select wire:model="locale" label="{{ __('users.select_locale') }}" placeholder="{{ __('users.select_locale') }}" name="locale">
            @foreach($locales as $key => $locale)
                <flux:select.option value="{{ $key }}">{{ $locale }}</flux:select.option>
            @endforeach
        </flux:select>
        {{-- Cambiar contraseña (opcional) --}}
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <div class="mb-3 text-sm font-medium text-zinc-700 dark:text-zinc-200">
                {{ __('Cambiar contraseña') ?? 'Cambiar contraseña (opcional)' }}
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <flux:input
                    wire:model.live="new_password"
                    id="new_password"
                    :label="__('global.password') ?? 'Nueva contraseña'"
                    :type="$this->passwordVisible ? 'text' : 'password'"
                    name="new_password"
                    autocomplete="new-password"
                    placeholder="{{ __('global.password') ?? 'Nueva contraseña' }}"
                >
                    <x-slot name="iconTrailing">
                        <flux:button size="sm" variant="subtle"
                                    icon="{{ $this->passwordVisible ? 'eye-slash' : 'eye' }}"
                                    class="-mr-1"
                                    wire:click.prevent="$toggle('passwordVisible')"/>
                    </x-slot>
                </flux:input>

                <flux:input
                    wire:model.live="new_password_confirmation"
                    id="new_password_confirmation"
                    :label="__('global.confirm_password') ?? 'Confirmar contraseña'"
                    :type="$this->confirmationPasswordVisible ? 'text' : 'password'"
                    name="new_password_confirmation"
                    autocomplete="new-password"
                    placeholder="{{ __('global.confirm_password') ?? 'Confirmar contraseña' }}"
                >
                    <x-slot name="iconTrailing">
                        <flux:button size="sm" variant="subtle"
                                    icon="{{ $this->confirmationPasswordVisible ? 'eye-slash' : 'eye' }}"
                                    class="-mr-1"
                                    wire:click.prevent="$toggle('confirmationPasswordVisible')"/>
                    </x-slot>
                </flux:input>
            </div>
        </div>

        <flux:checkbox.group wire:model.live="userRoles" label="{{ __('users.roles') }}" description="{{ __('users.roles_description') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @foreach($roles as $role)
                <flux:checkbox label="{{$role->name}}" value="{{$role->id}}"/>
            @endforeach
        </flux:checkbox.group>

        <flux:button type="submit" icon="save" variant="primary">
            {{ __('users.update_user') }}
        </flux:button>
    </x-form>

</section>
