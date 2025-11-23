<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl min-h-[40rem]">

    @if($this->isAdmin)
        {{-- ===========================
             DASHBOARD PARA ADMIN
        ============================ --}}

        {{-- TOP: Tarjetas de apartados (3 columnas) --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            {{-- 1) Solicitudes --}}
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            {{-- icono file-text --}}
                            <svg class="h-6 w-6 text-sky-600 dark:text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6"/>
                                <path d="M16 13H8"/>
                                <path d="M16 17H8"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                                Solicitudes
                            </h3>
                        </div>

                        <span class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                            {{ $this->solicitudesStats['total'] ?? 0 }}
                        </span>
                    </div>

                    <div class="mt-3 grid grid-cols-3 gap-2 text-xs text-neutral-600 dark:text-neutral-400">
                        <div>
                            <div class="font-semibold">En revisión</div>
                            <div class="text-base text-neutral-900 dark:text-neutral-100">
                                {{ $this->solicitudesStats['en_revision'] ?? 0 }}
                            </div>
                        </div>
                        <div>
                            <div class="font-semibold">Aprobadas</div>
                            <div class="text-base text-neutral-900 dark:text-neutral-100">
                                {{ $this->solicitudesStats['aprobadas'] ?? 0 }}
                            </div>
                        </div>
                        <div>
                            <div class="font-semibold">Rechazadas</div>
                            <div class="text-base text-neutral-900 dark:text-neutral-100">
                                {{ $this->solicitudesStats['rechazadas'] ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between text-sm">
                        <p class="text-neutral-500 dark:text-neutral-400">
                            Estado global de las solicitudes de documento.
                        </p>
                        <a href="{{ route('calidad.solicitudes.revisar') }}"
                           class="inline-flex items-center gap-1 text-xs font-medium text-sky-700 hover:underline dark:text-sky-300">
                            Ver detalle
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2) Lista Maestra --}}
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            {{-- icono archive --}}
                            <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="4" rx="1"/>
                                <path d="M5 7v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7"/>
                                <path d="M10 12h4"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                                Lista maestra
                            </h3>
                        </div>

                        <span class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                            {{ $this->listaMaestraStats['total_documentos'] ?? 0 }}
                        </span>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-neutral-600 dark:text-neutral-400">
                        <div>
                            <div class="font-semibold">Archivos</div>
                            <div class="text-base text-neutral-900 dark:text-neutral-100">
                                {{ $this->listaMaestraStats['total_archivos'] ?? 0 }}
                            </div>
                        </div>
                        <div>
                            <div class="font-semibold">Carpetas</div>
                            <div class="text-base text-neutral-900 dark:text-neutral-100">
                                {{ $this->listaMaestraStats['total_carpetas'] ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between text-sm">
                        <p class="text-neutral-500 dark:text-neutral-400">
                            Documentos controlados del SGC.
                        </p>
                        <a href="{{ route('calidad.lista-maestra.index') }}"
                           class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 hover:underline dark:text-amber-300">
                            Ver lista maestra
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3) Usuarios / Roles SGC --}}
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
                <div class="relative z-10 flex h-full flex-col">

                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            {{-- icono usuarios --}}
                            <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                                Usuarios del sistema
                            </h3>
                        </div>

                        {{-- Total de usuarios con rol o sin rol --}}
                        <span class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">
                            {{ $this->generalStats['total_staff'] ?? 0 }}
                        </span>
                    </div>

                    {{-- Desglose dinámico por rol --}}
                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-neutral-600 dark:text-neutral-400">
                        @foreach($this->rolesStats as $role)
                            <div>
                                <div class="font-semibold">
                                    {{ $role['name'] }}
                                </div>
                                <div class="text-base text-neutral-900 dark:text-neutral-100">
                                    {{ $role['users_count'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pie de tarjeta --}}
                    <div class="mt-auto flex items-center justify-between text-sm pt-3">
                        <p class="text-neutral-500 dark:text-neutral-400">
                            Distribución de usuarios del sistema.
                        </p>
                        <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 hover:underline dark:text-emerald-300">
                            Ver usuarios
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- MIDDLE: Panel de gráfica de solicitudes --}}
        <div class="relative rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
            <div class="relative z-10">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        {{-- icono line-chart --}}
                        <svg class="h-6 w-6 text-violet-600 dark:text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18"/>
                            <path d="M7 15l4-4 3 3 5-7"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                            Estadísticas de solicitudes
                        </h3>
                    </div>
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">
                        Distribución por estado (barras proporcionales)
                    </span>
                </div>

                @php
                    $totalSolicitudes = $this->solicitudesStats['total'] ?? 0;
                    $enRevision = $this->solicitudesStats['en_revision'] ?? 0;
                    $aprobadas = $this->solicitudesStats['aprobadas'] ?? 0;
                    $rechazadas = $this->solicitudesStats['rechazadas'] ?? 0;

                    $percent = function(int $value, int $total): int {
                        if ($total <= 0) return 0;
                        return (int) round(($value / $total) * 100);
                    };
                @endphp

                @if($totalSolicitudes === 0)
                    <div class="rounded-lg border border-dashed border-neutral-300 bg-neutral-50/70 p-6 text-center text-sm text-neutral-500 dark:border-neutral-700 dark:bg-neutral-900/40 dark:text-neutral-400">
                        Aún no hay solicitudes registradas para mostrar estadísticas.
                    </div>
                @else
                    <div class="space-y-4">
                        {{-- Barra: En revisión --}}
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs">
                                <span class="font-medium text-neutral-700 dark:text-neutral-200">
                                    En revisión
                                </span>
                                <span class="text-neutral-500 dark:text-neutral-400">
                                    {{ $enRevision }} ({{ $percent($enRevision, $totalSolicitudes) }}%)
                                </span>
                            </div>
                            <div class="h-3 w-full rounded-full bg-neutral-100 dark:bg-neutral-800 overflow-hidden">
                                <div class="h-full rounded-full bg-amber-500 dark:bg-amber-400"
                                    style="width: {{ $percent($enRevision, $totalSolicitudes) }}%">
                                </div>
                            </div>
                        </div>

                        {{-- Barra: Aprobadas --}}
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs">
                                <span class="font-medium text-neutral-700 dark:text-neutral-200">
                                    Aprobadas
                                </span>
                                <span class="text-neutral-500 dark:text-neutral-400">
                                    {{ $aprobadas }} ({{ $percent($aprobadas, $totalSolicitudes) }}%)
                                </span>
                            </div>
                            <div class="h-3 w-full rounded-full bg-neutral-100 dark:bg-neutral-800 overflow-hidden">
                                <div class="h-full rounded-full bg-emerald-500 dark:bg-emerald-400"
                                    style="width: {{ $percent($aprobadas, $totalSolicitudes) }}%">
                                </div>
                            </div>
                        </div>

                        {{-- Barra: Rechazadas --}}
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs">
                                <span class="font-medium text-neutral-700 dark:text-neutral-200">
                                    Rechazadas
                                </span>
                                <span class="text-neutral-500 dark:text-neutral-400">
                                    {{ $rechazadas }} ({{ $percent($rechazadas, $totalSolicitudes) }}%)
                                </span>
                            </div>
                            <div class="h-3 w-full rounded-full bg-neutral-100 dark:bg-neutral-800 overflow-hidden">
                                <div class="h-full rounded-full bg-rose-500 dark:bg-rose-400"
                                    style="width: {{ $percent($rechazadas, $totalSolicitudes) }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>


        {{-- BOTTOM: Panel Documentación / accesos rápidos --}}
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
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                            Documentación y accesos rápidos
                        </h3>
                    </div>
                    <a href="{{ route('calidad.lista-maestra.index') }}"
                       class="rounded-lg px-3 py-1.5 text-sm text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        Ver lista maestra
                    </a>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Lista maestra PDF rápido --}}
                    <a href="{{ route('calidad.lista-maestra.pdf.quick') }}"
                       class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                        <p class="font-medium text-neutral-800 dark:text-neutral-100">Lista maestra (PDF)</p>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Versión rápida para consulta.</p>
                    </a>

                    {{-- ZIP general de documentos --}}
                    <a href="{{ route('lista-maestra.zip-all') }}"
                       class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                        <p class="font-medium text-neutral-800 dark:text-neutral-100">Descarga masiva</p>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">ZIP con los documentos vigentes.</p>
                    </a>

                    {{-- Organización / personal --}}
                    <a href="{{ route('calidad.organizacion.personal') }}"
                       class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                        <p class="font-medium text-neutral-800 dark:text-neutral-100">Organización / personal</p>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Responsables del SGC por puesto.</p>
                    </a>

                    {{-- Quejas y sugerencias --}}
                    <a href="{{ route('admin.quejas.index') }}"
                       class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                        <p class="font-medium text-neutral-800 dark:text-neutral-100">Quejas y sugerencias</p>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Revisión de buzón de alumnos.</p>
                    </a>

                    {{-- Crear solicitud --}}
                    <a href="{{ route('calidad.solicitudes.crear') }}"
                       class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                        <p class="font-medium text-neutral-800 dark:text-neutral-100">Nueva solicitud</p>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Crear / modificar / dar de baja un documento.</p>
                    </a>

                    {{-- Estado de solicitudes --}}
                    <a href="{{ route('calidad.solicitudes.estado') }}"
                       class="group rounded-lg border border-neutral-200 bg-white/70 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-800">
                        <p class="font-medium text-neutral-800 dark:text-neutral-100">Estado de solicitudes</p>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Seguimiento por folio.</p>
                    </a>
                </div>

                <div class="mt-4 flex-1"></div>
            </div>
        </div>

    @else
        {{-- ===========================
             DASHBOARD PARA USUARIO NO ADMIN
             (docente / personal sin rol alto O alumno)
        ============================ --}}
        <div class="relative h-full flex-1 rounded-xl border border-neutral-200 bg-white p-8 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10"/>
            <div class="relative z-10 flex h-full flex-col justify-center">
                <div class="max-w-xl">
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-50 mb-2">
                        Bienvenido al Sistema de Gestión de la Calidad
                    </h1>

                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-4">
                        Hola {{ $this->currentUserName ?: 'usuario' }}, gracias por utilizar el SGC del Instituto
                        Tecnológico de Tuxtepec.
                    </p>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-lg border border-neutral-200 bg-white/80 p-4 dark:border-neutral-700 dark:bg-neutral-900/70">
                            <div class="text-xs font-semibold uppercase text-neutral-500 dark:text-neutral-400 mb-1">
                                Tus accesos
                            </div>
                            <p class="text-sm text-neutral-700 dark:text-neutral-200">
                                Desde el menú lateral puedes entrar a los módulos a los que tienes permiso
                                (lista maestra, solicitudes, quejas, etc.). El contenido que veas dependerá de tu rol.
                            </p>
                        </div>

                        <div class="rounded-lg border border-neutral-200 bg-white/80 p-4 dark:border-neutral-700 dark:bg-neutral-900/70">
                            <div class="text-xs font-semibold uppercase text-neutral-500 dark:text-neutral-400 mb-1">
                                Soporte
                            </div>
                            <p class="text-sm text-neutral-700 dark:text-neutral-200">
                                Si tienes dudas sobre el uso del sistema o necesitas que te asignen permisos,
                                contacta a la Coordinación de Calidad o al administrador del SGC.
                            </p>
                        </div>
                    </div>

                    @if($this->isStudent)
                        <div class="mt-6 rounded-lg border border-sky-200 bg-sky-50/80 p-4 text-sm text-sky-900 dark:border-sky-800 dark:bg-sky-900/40 dark:text-sky-100">
                            Recuerda que desde el menú puedes registrar nuevas quejas/sugerencias y
                            consultar el estado de las que ya enviaste.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
