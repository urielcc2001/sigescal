<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-r dark:border-neutral-800">
                <div class="absolute inset-0 bg-neutral-900"></div>
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium">
                    <span class="flex h-10 w-10 items-center justify-center rounded-md">
                        <x-app-logo-icon class="mr-2 h-7 fill-current text-white" />
                    </span>
                    {{ config('app.name', 'Laravel') }}
                </a>
                @php
                    $loginImage = asset('logos/login.png'); // public/logos/login.png
                @endphp

                <div class="relative z-20 mt-auto flex h-full items-center justify-center">
                    <div class="relative w-full max-w-md">
                        <img
                            src="{{ $loginImage }}"
                            alt="Login illustration"
                            class="w-full h-auto select-none drop-shadow-xl"
                            loading="lazy"
                            decoding="async"
                        />

                        <!-- Decorativos suaves -->
                        <div aria-hidden="true" class="pointer-events-none">
                            <!-- Claro -->
                            <div class="absolute -top-16 -left-20 h-56 w-56 rounded-full bg-gradient-to-br from-sky-400/30 to-indigo-400/30 blur-2xl dark:hidden"></div>
                            <div class="absolute -bottom-24 -right-16 h-64 w-64 rounded-full bg-gradient-to-tr from-green-400/25 to-teal-400/25 blur-3xl dark:hidden"></div>

                            <!-- Oscuro -->
                            <div class="absolute -top-16 -left-20 hidden h-56 w-56 rounded-full bg-gradient-to-br from-fuchsia-500/30 to-violet-500/30 blur-2xl dark:block"></div>
                            <div class="absolute -bottom-24 -right-16 hidden h-64 w-64 rounded-full bg-gradient-to-tr from-rose-500/25 to-purple-500/25 blur-3xl dark:block"></div>

                            <!-- Aro central translúcido (funciona en ambos) -->
                            <div class="absolute top-1/2 left-1/2 -z-10 h-96 w-96 -translate-x-1/2 -translate-y-1/2 rounded-full bg-black/5 ring-1 ring-white/10 dark:bg-white/5"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden">
                        <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                        </span>

                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
