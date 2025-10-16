<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    {{-- Header compartido, SIN menú móvil --}}
    @include('partials.site-header', ['showMobileMenu' => false])

    {{-- Sidebar del Dashboard --}}
    <flux:sidebar sticky stashable
        class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 lg:dark:bg-zinc-900/50">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

        <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
            <x-app-logo class="size-8" />
            <span class="hidden lg:inline text-sm font-semibold">Panel</span>
        </a>

        <div class="mb-4">
            <flux:button href="{{ route('home') }}" icon="arrow-left" size="sm" wire:navigate>
                Ir al inicio
            </flux:button>
        </div>

        <flux:navlist variant="outline">
            <flux:navlist.group heading="Panel" class="grid">
                <flux:navlist.item icon="home"
                    :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    Inicio
                </flux:navlist.item>
                {{-- Más secciones... --}}
                {{-- <flux:navlist.item icon="folder"
                    :href="route('dashboard.documentos')"
                    :current="request()->routeIs('dashboard.documentos')"
                    wire:navigate>
                    Documentos
                </flux:navlist.item> --}}
            </flux:navlist.group>

            <flux:navlist.group heading="Solicitudes" class="grid">
                <flux:navlist.item
                    icon="plus-circle"
                    href="{{ route('calidad.solicitudes.crear') }}"
                    :current="request()->routeIs('calidad.solicitudes.crear')"
                    wire:navigate>
                    Crear solicitud
                </flux:navlist.item>

                <flux:navlist.item
                    icon="list-bullet"
                    href="{{ route('calidad.solicitudes.estado') }}"
                    :current="request()->routeIs('calidad.solicitudes.estado')"
                    wire:navigate>
                    Estado de solicitudes
                </flux:navlist.item>

                <flux:navlist.item
                    icon="check-circle"
                    href="{{ route('calidad.solicitudes.revisar') }}"
                    :current="request()->routeIs('calidad.solicitudes.revisar')"
                    wire:navigate>
                    Revisar solicitudes
                </flux:navlist.item>

            </flux:navlist.group>

            <flux:navlist.group heading="Lista Maestra" class="grid">
                <flux:navlist.item
                    icon="book-open"
                    href="{{ route('calidad.lista-maestra.index') }}"
                    :current="request()->routeIs('calidad.lista-maestra.index')"
                    wire:navigate>
                    Ver lista maestra
                </flux:navlist.item>
            </flux:navlist.group>

            <flux:navlist.group heading="Organización" class="grid">
                <flux:navlist.item
                    icon="users"
                    href="{{ route('calidad.organizacion.personal') }}"
                    :current="request()->routeIs('calidad.organizacion.personal')"
                    wire:navigate
                >
                    Personal
                </flux:navlist.item>
            </flux:navlist.group>


            <flux:navlist.group heading="Cuenta" class="grid">
                <flux:navlist.item icon="user"
                    href="/settings/profile"
                    :current="request()->is('settings/profile')"
                    wire:navigate>
                    Mi perfil
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer/>
    </flux:sidebar>

    {{-- Contenido (lo inyecta el wrapper) --}}
    {{ $slot }}

    @fluxScripts
    <x-livewire-alert::scripts />
    <x-livewire-alert::flash />
</body>
</html>
