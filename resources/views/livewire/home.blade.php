<div class="max-w-5xl mx-auto py-16 px-6 grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
    {{-- Columna izquierda: TecNM arriba + Texto abajo --}}
    <div class="flex flex-col items-center md:items-start gap-8">
        {{-- Logo TecNM --}}
        <div class="h-16 w-28 flex items-center justify-center">
            <img src="{{ asset('logos/Logo-TecNM.png') }}" 
                 alt="TecNM" 
                 class="max-h-full max-w-full object-contain opacity-90">
        </div>

        {{-- Texto de bienvenida --}}
        <div class="text-center md:text-left">
            <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white">
                Bienvenidos al Sistema de Gestión de la Calidad
            </h1>
            <p class="mt-4 text-lg text-zinc-600 dark:text-zinc-400">
                <span class="font-semibold">SGC del ITTux</span> · Instituto Tecnológico de Tuxtepec
            </p>
        </div>
    </div>

    {{-- Columna derecha: ITTux arriba --}}
    <div class="flex flex-col items-center md:items-start">
        <div class="flex items-center justify-center">
            <img src="{{ asset('logos/Logo_ITTux.png') }}" 
                 alt="ITTux" 
                 class="h-12 w-auto object-contain opacity-90 scale-[.85]">
        </div>
    </div>
</div>
