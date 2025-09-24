<div class="max-w-5xl mx-auto px-6 py-16">
  <div class="relative overflow-hidden rounded-2xl shadow-lg ring-1 ring-black/5">
    {{-- Imagen grande --}}
    <img
      src="{{ asset('logos/home.png') }}"
      alt="Portada SGC ITTux"
      class="block w-full h-[26rem] sm:h-[32rem] md:h-[38rem] lg:h-[42rem] object-cover"
      loading="eager" decoding="async">

    {{-- Degradado superior para legibilidad --}}
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/60 via-black/25 to-transparent"></div>

    {{-- Texto dentro de la imagen (arriba) --}}
    <div class="absolute top-0 left-0 right-0 p-6 sm:p-8 md:p-10">
      <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5
                   text-base sm:text-lg font-bold
                   bg-white/85 text-zinc-900 shadow-sm backdrop-blur-sm
                   dark:bg-zinc-900/70 dark:text-white">
        SGC · ITTux
      </span>

      <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5
                   text-base sm:text-lg font-bold
                   bg-white/85 text-zinc-900 shadow-sm backdrop-blur-sm
                   dark:bg-zinc-900/70 dark:text-white">
            BIENVENIDOS AL SISTEMA DE GESTION DE LA CALIDAD 
      </span>

      <p class="mt-4 text-white/95 font-bold
                text-2xl sm:text-3xl md:text-4xl">
        Instituto Tecnológico de Tuxtepec
      </p>
    </div>
  </div>
</div>
