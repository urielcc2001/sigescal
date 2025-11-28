{{-- HERO FULL WIDTH --}}
<div class="relative w-full h-[26rem] sm:h-[32rem] md:h-[38rem] lg:h-[42rem]">

    {{-- Imagen --}}
    <img
        src="{{ asset('logos/home.png') }}"
        alt="SGC ITTux"
        class="absolute inset-0 w-full h-full object-cover"
    >

    {{-- Degradado --}}
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>

    {{-- Contenido --}}
    <div class="relative z-10 h-full flex flex-col justify-between px-6 sm:px-10 md:px-16 py-10">

        {{-- FILA SUPERIOR --}}
        <div class="flex items-start justify-between">
            {{-- Chip + descripción --}}
            <div class="space-y-2 max-w-lg">
                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5
                            text-xs sm:text-sm font-semibold tracking-wide
                            bg-white/90 text-zinc-900 shadow-sm backdrop-blur
                            dark:bg-zinc-900/80 dark:text-white">
                    SGC · Instituto Tecnológico de Tuxtepec
                </span>

                <p class="text-white/80 text-sm sm:text-base leading-relaxed">
                    Sistema de Gestión de la Calidad del ITTux.
                </p>
            </div>

            {{-- Botones a la derecha --}}
            <div class="flex flex-col items-end gap-2">
                <a href="{{ route('quejas.form') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-4 py-2 text-sm font-semibold
                          text-white shadow-lg shadow-rose-600/40 hover:bg-rose-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    Enviar queja o sugerencia
                </a>
                {{--
                @if(Route::has('quejas.estado-publico'))
                <a href="{{ route('quejas.estado-publico') }}"
                   class="inline-flex items-center gap-1 rounded-full border border-white/70 bg-white/10 px-3 py-1.5
                          text-xs sm:text-sm font-medium text-white hover:bg-white/20 transition backdrop-blur">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-4.35-4.35M11 5a6 6 0 100 12 6 6 0 000-12z" />
                    </svg>
                    Consultar estado de mi queja
                </a>
                @endif
                --}}
            </div>
        </div>

        {{-- TÍTULO PRINCIPAL ABAJO --}}
        <div class="space-y-2">
            <p class="text-sm font-semibold tracking-[0.2em] text-white/80 uppercase">
                SGC · ITTux
            </p>
            <p class="text-3xl sm:text-4xl md:text-5xl font-bold text-white drop-shadow">
                Sistema de Gestión de la Calidad
            </p>
        </div>
    </div>
</div>
