<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl min-h-[40rem]">
    {{-- TOP: Tarjetas de apartados --}}
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">

        {{-- Documentos --}}
        <a href="#" class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
            <div class="relative z-10 flex h-full flex-col justify-between">
                <div class="flex items-center gap-3">
                    {{-- icono file-text --}}
                    <svg class="h-6 w-6 text-sky-600 dark:text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                        <path d="M16 13H8"/>
                        <path d="M16 17H8"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Documentos</h3>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <p class="text-neutral-500 dark:text-neutral-400">Sube y organiza archivos</p>
                    <svg class="h-5 w-5 text-neutral-400 dark:text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </div>
        </a>

        {{-- Archivados --}}
        <a href="#" class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
            <div class="relative z-10 flex h-full flex-col justify-between">
                <div class="flex items-center gap-3">
                    {{-- icono archive --}}
                    <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="4" rx="1"/>
                        <path d="M5 7v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7"/>
                        <path d="M10 12h4"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Archivados</h3>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <p class="text-neutral-500 dark:text-neutral-400">Consulta históricos</p>
                    <svg class="h-5 w-5 text-neutral-400 dark:text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </div>
        </a>

        {{-- Docentes --}}
        <a href="#" class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
            <div class="relative z-10 flex h-full flex-col justify-between">
                <div class="flex items-center gap-3">
                    {{-- icono graduation-cap --}}
                    <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10L12 5 2 10l10 5 10-5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        <path d="M22 20v-10"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Docentes</h3>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <p class="text-neutral-500 dark:text-neutral-400">Gestión de personal académico</p>
                    <svg class="h-5 w-5 text-neutral-400 dark:text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </div>
        </a>
        <a href="#" class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
            <div class="relative z-10 flex h-full flex-col justify-between">
                <div class="flex items-center gap-3">
                    {{-- icono line-chart --}}
                    <svg class="h-6 w-6 text-violet-600 dark:text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18"/>
                        <path d="M7 15l4-4 3 3 5-7"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Estadisticas</h3>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <p class="text-neutral-500 dark:text-neutral-400">Indicadores y métricas</p>
                    <svg class="h-5 w-5 text-neutral-400 dark:text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    {{-- BOTTOM: Panel Documentación --}}
    <div class="relative h-full flex-1 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
        <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
        <div class="relative z-10 flex h-full flex-col">
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- icono book-open --}}
                    <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14"/>
                        <path d="M2 3v14a4 4 0 0 0 4 4h6"/>
                        <path d="M22 3v14a4 4 0 0 1-4 4h-6"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Documentación</h3>
                </div>
                <a href="#" class="rounded-lg px-3 py-1.5 text-sm text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">Ver todo</a>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <a href="#" class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                    <p class="font-medium text-neutral-800 dark:text-neutral-100">Guías</p>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Procedimientos y manuales</p>
                </a>
                <a href="#" class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                    <p class="font-medium text-neutral-800 dark:text-neutral-100">Políticas</p>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Reglas y lineamientos</p>
                </a>
                <a href="#" class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                    <p class="font-medium text-neutral-800 dark:text-neutral-100">Formatos</p>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Plantillas descargables</p>
                </a>
                <a href="#" class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                    <p class="font-medium text-neutral-800 dark:text-neutral-100">Preguntas Frecuentes</p>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Resuelve dudas rápido</p>
                </a>
                <a href="#" class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                    <p class="font-medium text-neutral-800 dark:text-neutral-100">Changelog</p>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Últimos cambios del sistema</p>
                </a>
                <a href="#" class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                    <p class="font-medium text-neutral-800 dark:text-neutral-100">Soporte</p>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Contacto y tickets</p>
                </a>
            </div>

            {{-- espacio flexible para que el panel crezca si lo necesitas --}}
            <div class="mt-4 flex-1"></div>
        </div>
    </div>
</div>
