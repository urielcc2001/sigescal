<div class="space-y-6">

    {{-- Encabezado institucional --}}
    <div class="text-center space-y-1">
        <h2 class="text-xs tracking-widest">SUBDIRECCIÓN PLANEACIÓN Y VINCULACIÓN</h2>
        <h2 class="text-xs tracking-widest">COORDINACIÓN DE CALIDAD</h2>

        <h1 class="mt-3 text-lg font-bold uppercase">Solicitud de creación y actualización de documentos</h1>

        {{-- Subtítulo del procedimiento --}}
        <p class="text-sm font-semibold">Procedimiento para el Control de la Información Documentada</p>
    </div>

    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    

    {{-- Bloque: Folio y fecha --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">FOLIO N°</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50"
                       wire:model="folio" readonly>
                @error('folio') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">FECHA</label>
                <input type="date" class="mt-1 w-full rounded-md border p-2"
                       wire:model="fecha">
                @error('fecha') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
    @php $noAreas = !auth()->user()->hasAnyRole(['Super Admin','Admin']) && !auth()->user()->areas()->exists(); @endphp
    @if($noAreas)
    <div class="rounded-md border border-amber-300 bg-amber-50 p-3 text-amber-900">No tienes áreas asignadas. Pide a un administrador que te asigne una.</div>
    @endif
    {{-- Bloque: Descripción del documento --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL DOCUMENTO</div>

        {{-- Input por CÓDIGO con datalist: muestra "código — título" en la lista, pero al elegir queda solo el código --}}
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Código del documento</label>
                <input type="text"
                    class="mt-1 w-full rounded-md border p-2"
                    list="lista-codigos"
                    placeholder="Escribe o selecciona..."
                    wire:model.live="codigo">
                <datalist id="lista-codigos">
                    @foreach($documentos as $doc)
                        <option value="{{ $doc->codigo }}" label="{{ $doc->codigo }} — {{ \Illuminate\Support\Str::limit($doc->nombre, 80) }}"></option>
                    @endforeach
                </datalist>
                @error('documento_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Área</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50"
                    value="{{ optional($areas->firstWhere('id', $area_id))->nombre }}" readonly>
                @error('area_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>

        @php
            $docSel = $documentos->firstWhere('id', (int) $documento_id);
        @endphp

        {{-- Ya NO repetimos el campo "Código". Solo mostramos info complementaria --}}
        <div class="grid md:grid-cols-3 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium">Revisión actual</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50"
                    value="{{ $docSel?->revision }}" readonly>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Título</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50"
                    value="{{ $docSel?->nombre }}" readonly>
            </div>
        </div>
    </div>


    {{-- Bloque: Tipo de trámite --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="text-sm font-semibold mb-3">TIPO DE TRÁMITE</div>

        <div class="max-w-sm">
            <label class="block text-sm font-medium">Selecciona el tipo</label>
            <select class="mt-1 w-full rounded-md border p-2" wire:model="tipo">
                @foreach($tipos as $t)
                    <option value="{{ $t }}">{{ strtoupper($t) }}</option>
                @endforeach
            </select>
        </div>

        @error('tipo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Bloque: Descripción del cambio --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL CAMBIO</div>

        <div class="space-y-6">
            {{-- DICE --}}
            <div>
                <label class="block text-sm font-medium">Dice</label>
                <textarea rows="8" class="mt-1 w-full rounded-md border p-2"
                        wire:model.defer="cambio_dice"></textarea>
                @error('cambio_dice') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror

                <div class="mt-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                            Agregar imágenes (opcional)
                        </span>

                        {{-- Botón con ícono para adjuntar --}}
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="file" accept="image/*" multiple wire:model="imagenesDice" class="hidden">
                            <span class="inline-flex items-center justify-center rounded-md border
                                        border-zinc-300 bg-white px-3 py-1.5 text-sm font-medium
                                        text-zinc-700 shadow-sm hover:bg-zinc-50
                                        dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                                {{-- ícono imagen --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 12.5l2.5 3 3.5-4.5L20 17H4l4.5-4.5Z"/>
                                    <circle cx="8" cy="7.5" r="1"/>
                                </svg>
                                Adjuntar
                            </span>
                        </label>
                    </div>

                    @error('imagenesDice.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

                    @if(!empty($imagenesDice))
                        <div class="mt-2 flex flex-wrap gap-3">
                            @foreach($imagenesDice as $idx => $img)
                                <div class="relative">
                                    <img src="{{ $img->temporaryUrl() }}"
                                        class="h-24 w-24 object-cover rounded-md border dark:border-zinc-700">

                                    <button type="button"
                                            wire:click="removeDice({{ $idx }})"  {{-- en DebeDecir usa removeDebeDecir --}}
                                            class="absolute -top-2 -right-2 inline-flex h-8 w-8 items-center justify-center rounded-full
                                                border shadow-lg ring-1
                                                bg-white text-red-600 border-red-500 ring-black/10      {{-- modo claro --}}
                                                dark:bg-red-600 dark:text-white dark:border-red-600 dark:ring-zinc-900  {{-- modo oscuro --}}
                                                hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-black/20 dark:focus:ring-white/30"
                                            aria-label="Quitar imagen"
                                            style="backdrop-filter: blur(2px);">
                                        {{-- Icono basurero (usa currentColor) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-5 w-5" fill="currentColor">
                                            <path d="M9 3a1 1 0 0 0-1 1v1H5.5a1 1 0 1 0 0 2H6v11a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V7h.5a1 1 0 1 0 0-2H16V4a1 1 0 0 0-1-1H9Zm2 2h2V5h-2V5Zm-3 4h8v10a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V9Zm2 2a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Zm4 0a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- DEBE DECIR --}}
            <div>
                <label class="block text-sm font-medium">Debe decir</label>
                <textarea rows="8" class="mt-1 w-full rounded-md border p-2"
                        wire:model.defer="cambio_debe_decir"></textarea>
                @error('cambio_debe_decir') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror

                <div class="mt-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                            Agregar imágenes (opcional)
                        </span>

                        {{-- Botón con ícono para adjuntar --}}
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="file" accept="image/*" multiple wire:model="imagenesDebeDecir" class="hidden">
                            <span class="inline-flex items-center justify-center rounded-md border
                                        border-zinc-300 bg-white px-3 py-1.5 text-sm font-medium
                                        text-zinc-700 shadow-sm hover:bg-zinc-50
                                        dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 12.5l2.5 3 3.5-4.5L20 17H4l4.5-4.5Z"/>
                                    <circle cx="8" cy="7.5" r="1"/>
                                </svg>
                                Adjuntar
                            </span>
                        </label>
                    </div>

                    @error('imagenesDebeDecir.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

                    @if(!empty($imagenesDebeDecir))
                        <div class="mt-2 flex flex-wrap gap-3">
                            @foreach($imagenesDebeDecir as $idx => $img)
                                <div class="relative">
                                    <img src="{{ $img->temporaryUrl() }}"
                                        class="h-24 w-24 object-cover rounded-md border dark:border-zinc-700">

                                    <button type="button"
                                            wire:click="removeDebeDecir({{ $idx }})"
                                            class="absolute -top-2 -right-2 inline-flex h-8 w-8 items-center justify-center rounded-full
                                                border shadow-lg ring-1
                                                bg-white text-red-600 border-red-500 ring-black/10      {{-- modo claro --}}
                                                dark:bg-red-600 dark:text-white dark:border-red-600 dark:ring-zinc-900  {{-- modo oscuro --}}
                                                hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-black/20 dark:focus:ring-white/30"
                                            aria-label="Quitar imagen"
                                            style="backdrop-filter: blur(2px);">
                                        {{-- Icono basurero (usa currentColor) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-5 w-5" fill="currentColor">
                                            <path d="M9 3a1 1 0 0 0-1 1v1H5.5a1 1 0 1 0 0 2H6v11a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V7h.5a1 1 0 1 0 0-2H16V4a1 1 0 0 0-1-1H9Zm2 2h2V5h-2V5Zm-3 4h8v10a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V9Zm2 2a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Zm4 0a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Bloque: Justificación --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="text-sm font-semibold mb-3">JUSTIFICACIÓN DE LA SOLICITUD</div>
        <textarea rows="4" class="mt-1 w-full rounded-md border p-2"
                  wire:model.defer="justificacion"></textarea>
        @error('justificacion') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Bloque: Requiere capacitación / Difusión --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <div class="text-sm font-semibold mb-2">REQUIERE CAPACITACIÓN</div>
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" value="1" wire:model="requiere_capacitacion"> <span>SI</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" value="0" wire:model="requiere_capacitacion"> <span>NO</span>
                    </label>
                </div>
            </div>

            <div>
                <div class="text-sm font-semibold mb-2">DIFUSIÓN</div>
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" value="1" wire:model="requiere_difusion"> <span>SI</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" value="0" wire:model="requiere_difusion"> <span>NO</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Botón centrado que abre el modal --}}
    <div class="flex justify-center pt-6">
        <flux:button variant="primary" icon="paper-airplane" wire:click="$set('showConfirm', true)">
            Enviar solicitud
        </flux:button>
    </div>

    {{-- Modal (patrón que ya te funciona) --}}
    <flux:modal wire:model="showConfirm" title="Confirmar envío" icon="question-mark-circle" size="md">
        <div class="space-y-3">
            <p class="text-sm text-zinc-600 dark:text-zinc-300">
                ¿Deseas enviar la solicitud ahora?
            </p>

            {{-- (Opcional) resumen breve de lo que se enviará --}}
            {{-- <div class="rounded-md border p-3 text-xs dark:border-zinc-700">
                <div><span class="font-medium">Folio:</span> {{ $solicitud->folio }}</div>
                <div><span class="font-medium">Documento:</span> {{ $docSel?->codigo }} — {{ $docSel?->nombre }}</div>
            </div> --}}

            <div class="mt-2 flex justify-end gap-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showConfirm', false)">
                    Cancelar
                </flux:button>

                <flux:button
                    type="button"
                    variant="primary"
                    icon="check-circle"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                >
                    Sí, enviar
                </flux:button>
            </div>
        </div>
    </flux:modal>


</div>
