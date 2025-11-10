<section class="w-full">
    {{-- Encabezado institucional --}}
    <div class="space-y-6">
        <div class="text-center space-y-1">
            <h2 class="text-xs tracking-widest">SUBDIRECCIÓN PLANEACIÓN Y VINCULACIÓN</h2>
            <h2 class="text-xs tracking-widest">COORDINACIÓN DE CALIDAD</h2>

            <h1 class="mt-3 text-lg font-bold uppercase">Solicitud de creación y actualización de documentos</h1>
            <p class="text-sm font-semibold">Procedimiento para el Control de la Información Documentada</p>
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- Badge de estado --}}
        <div class="flex justify-center">
            <span @class([
                'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium',
                'bg-yellow-100 text-yellow-800' => $solicitud->estado === 'en_revision',
                'bg-green-100 text-green-800' => $solicitud->estado === 'aprobada',
                'bg-red-100 text-red-800' => $solicitud->estado === 'rechazada',
            ])>
                Estado: {{ str_replace('_',' ', $solicitud->estado) }}
            </span>
        </div>

        {{-- Bloque: Folio y fecha --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">FOLIO N°</label>
                    <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                           value="{{ $solicitud->folio }}" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium">FECHA</label>
                    <input type="date" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                           value="{{ \Illuminate\Support\Carbon::parse($solicitud->fecha)->format('Y-m-d') }}" readonly>
                </div>
            </div>
        </div>

        {{-- Bloque: Descripción del documento --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL DOCUMENTO</div>

            @php
                $doc = $solicitud->documento; // ListaMaestra
                $area = $solicitud->area;
            @endphp

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Código del documento</label>
                    <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                           value="{{ $doc?->codigo }}" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium">Área</label>
                    <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                           value="{{ $area?->nombre }}" readonly>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium">Revisión actual</label>
                    <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                           value="{{ $doc?->revision }}" readonly>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Título</label>
                    <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                           value="{{ $doc?->nombre }}" readonly>
                </div>
            </div>
        </div>

        {{-- Bloque: Tipo de trámite --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="text-sm font-semibold mb-3">TIPO DE TRÁMITE</div>

            <div class="max-w-sm">
                <label class="block text-sm font-medium">Tipo</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 capitalize"
                       value="{{ $solicitud->tipo }}" readonly>
            </div>
        </div>

        {{-- Bloque: Descripción del cambio --}}
        @php
            use Illuminate\Support\Facades\Storage;
        @endphp

        {{-- Bloque: Descripción del cambio --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL CAMBIO</div>

            <div class="space-y-6">
                {{-- DICE --}}
                <div>
                    <label class="block text-sm font-medium">Dice</label>
                    <textarea rows="8" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                            readonly>{{ $solicitud->cambio_dice }}</textarea>

                    @if($solicitud->imagenesDice?->count())
                        <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($solicitud->imagenesDice as $img)
                                @php $url = Storage::disk($img->disk)->url($img->path); @endphp
                                <a href="{{ $url }}" target="_blank" class="group block">
                                    <img src="{{ $url }}" loading="lazy"
                                        class="h-28 w-full object-cover rounded-md border border-zinc-200 dark:border-zinc-700 group-hover:opacity-90" />
                                    <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $img->original_name }}</div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- DEBE DECIR --}}
                <div>
                    <label class="block text-sm font-medium">Debe decir</label>
                    <textarea rows="8" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                            readonly>{{ $solicitud->cambio_debe_decir }}</textarea>

                    @if($solicitud->imagenesDebeDecir?->count())
                        <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($solicitud->imagenesDebeDecir as $img)
                                @php $url = Storage::disk($img->disk)->url($img->path); @endphp
                                <a href="{{ $url }}" target="_blank" class="group block">
                                    <img src="{{ $url }}" loading="lazy"
                                        class="h-28 w-full object-cover rounded-md border border-zinc-200 dark:border-zinc-700 group-hover:opacity-90" />
                                    <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $img->original_name }}</div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bloque: Justificación --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="text-sm font-semibold mb-3">JUSTIFICACIÓN DE LA SOLICITUD</div>
            <textarea rows="4" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                      readonly>{{ $solicitud->justificacion }}</textarea>
        </div>

        {{-- Bloque: Requiere capacitación / Difusión --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm font-semibold mb-2">REQUIERE CAPACITACIÓN</div>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="inline-flex h-2 w-2 rounded-full {{ $solicitud->requiere_capacitacion ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                        {{ $solicitud->requiere_capacitacion ? 'Sí' : 'No' }}
                    </div>
                </div>

                <div>
                    <div class="text-sm font-semibold mb-2">DIFUSIÓN</div>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="inline-flex h-2 w-2 rounded-full {{ $solicitud->requiere_difusion ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                        {{ $solicitud->requiere_difusion ? 'Sí' : 'No' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Acción: Descargar formato (solo si Aprobada) --}}
        <div class="pt-2 flex flex-col items-center gap-1">
            @can('solicitudes.export')
                @if($solicitud->estado === 'aprobada')
                    <flux:button :href="route('calidad.solicitudes.estado.formato.pdf', $solicitud)" target="_blank"
                                class="!bg-indigo-600 hover:!bg-indigo-700 !text-white">
                    Descargar formato
                    </flux:button>
                @else
                    <flux:button
                        icon="lock-closed"
                        variant="outline"
                        disabled
                        title="Se habilitará hasta su aprobación.">
                        Descargar formato
                    </flux:button>
                    <small class="text-xs text-zinc-500">Se habilitará hasta su aprobación.</small>
                @endif
            @else
                <flux:button
                    icon="lock-closed"
                    variant="outline"
                    disabled
                    title="No tienes permiso para exportar.">
                    Descargar formato
                </flux:button>
                <small class="text-xs text-zinc-500">No tienes permiso para exportar.</small>
            @endcan
        </div>
    </div>
</section>
