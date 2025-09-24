@php
    // si no mandas nada, por defecto SÍ muestra el menú móvil
    $showMobileMenu = $showMobileMenu ?? true;
@endphp

{{-- Header (desktop) --}}
<flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    {{-- Logos institucionales --}}
    <div class="hidden lg:flex items-center gap-3 mr-4 shrink-0" style="height:70px">
        <img src="{{ asset('logos/Logo-TecNM.png') }}" alt="TecNM" style="height:100%; width:auto; display:block;" loading="eager" decoding="async">
        <img src="{{ asset('logos/Logo_ITTux.png') }}" alt="ITTux" style="height:100%; width:auto; display:block;" loading="eager" decoding="async">
    </div>

    <a href="{{ route('home') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
        <x-app-logo class="size-8"></x-app-logo>
    </a>

    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item icon="layout-grid" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')" wire:navigate>
            Dashboard
        </flux:navbar.item>
    </flux:navbar>

    <flux:spacer/>

    {{-- Toggle claro/oscuro --}}
    <div x-data class="mr-2">
        <button
            type="button"
            :aria-label="$flux.appearance === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
            @click="$flux.appearance = ($flux.appearance === 'dark' ? 'light' : 'dark')"
            class="inline-flex items-center justify-center rounded-xl p-2 hover:bg-zinc-200/70 dark:hover:bg-zinc-700/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-400 dark:focus:ring-zinc-600"
            title="Cambiar tema">
            <flux:icon.moon x-show="$flux.appearance !== 'dark'" class="size-5"/>
            <flux:icon.sun  x-show="$flux.appearance === 'dark'"  class="size-5"/>
        </button>
    </div>

    @if (Route::has('login'))
        <nav class="flex items-center justify-end gap-4">
            @guest
                <flux:button href="{{ route('login') }}" variant="primary" wire:navigate>
                    {{ __('global.log_in') }}
                </flux:button>
                @if (Route::has('register'))
                    <flux:button href="{{ route('register') }}" wire:navigate>
                        {{ __('global.register') }}
                    </flux:button>
                @endif
            @endguest
        </nav>
    @endif

    @auth
        @if (Session::has('admin_user_id'))
            <div class="py-2 flex items-center justify-center dark:text-white rounded mr-4">
                <form id="stop-impersonating" class="flex flex-col items-center gap-3" action="{{ route('impersonate.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <flux:button type="submit" size="sm" variant="danger" form="stop-impersonating" class="!w-full !flex !flex-row cursor-pointer">
                        <div class="flex items-center gap-2">
                            <flux:icon.loader-circle class="animate-spin mr-2"/>
                            {{ __('users.stop_impersonating') }}
                        </div>
                    </flux:button>
                </form>
            </div>
        @endif

        <!-- Desktop User Menu -->
        <flux:dropdown position="top" align="end">
            <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()"/>
            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                        </div>
                    </div>
                </flux:menu.radio.group>

                @can('access dashboard')
                    <flux:menu.separator/>
                    <flux:menu.radio.group>
                        <flux:menu.item href="{{ route('admin.index') }}" icon="shield" wire:navigate>
                            {{ __('global.admin_dashboard') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>
                @endcan

                <flux:menu.separator/>
                <flux:menu.radio.group>
                    <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>
                        {{ __('settings.title') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator/>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('global.log_out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    @endauth
</flux:header>

{{-- Menú móvil opcional del header (solo si $showMobileMenu === true) --}}
@if($showMobileMenu)
    <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>
        <a href="{{ route('dashboard') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
            <x-app-logo class="size-8"></x-app-logo>
        </a>
        @auth
            <flux:navlist variant="outline">
                <flux:navlist.group heading="Platform">
                    <flux:navlist.item icon="layout-grid" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')" wire:navigate>
                        Dashboard
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
        @endauth
        <flux:spacer/>
    </flux:sidebar>
@endif
