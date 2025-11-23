<section class="w-full">
    <x-page-heading>
        <x-slot:title>Editar solicitud rechazada</x-slot:title>
        <x-slot:subtitle>Corrige la información y envíala nuevamente a revisión</x-slot:subtitle>
    </x-page-heading>

    <div class="space-y-6">

        {{-- Motivo del rechazo --}}
        <div class="rounded-md border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-semibold">Motivo del rechazo</h4>
                @if($rechazoFecha)
                    <span class="text-xs opacity-80">{{ $rechazoFecha }}</span>
                @endif
            </div>
            <p class="mt-1 whitespace-pre-line text-sm">{{ $motivoRechazo ?: '—' }}</p>
            @if($rechazoPor)
                <p class="mt-1 text-xs opacity-80">Por: {{ $rechazoPor }}</p>
            @endif
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-50 p-3 text-green-800 dark:bg-green-900/20 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-md bg-red-50 p-3 text-red-800 dark:bg-red-900/20 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

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
                    <input type="date" class="mt-1 w-full rounded-md border p-2"
                           wire:model.live="fecha">
                    @error('fecha') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Bloque: Descripción del documento --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL DOCUMENTO</div>

            @if($tipo === 'creacion')
                {{-- MODO CREACIÓN: área + código editables --}}
                <div class="grid md:grid-cols-2 gap-4" wire:key="rechazo-creacion">
                    <div>
                        <label class="block text-sm font-medium">Área</label>
                        <select
                            class="mt-1 w-full rounded-md border px-3 py-2 bg-zinc-50 text-zinc-900 border-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 dark:bg-zinc-800 dark:text-zinc-100 dark:border-zinc-600 dark:[color-scheme:dark]"
                            wire:model.live="area_id">
                            <option value="">Selecciona un área</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">
                                    {{ $area->codigo }} — {{ $area->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Código del documento (propuesto)</label>
                        <input type="text"
                            class="mt-1 w-full rounded-md border p-2"
                            placeholder="Ej. ITTUX-AC-PO-001"
                            wire:model.live="codigo">
                        @error('codigo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mt-4" wire:key="rechazo-creacion-extra">
                    <div>
                        <label class="block text-sm font-medium">Revisión inicial</label>
                        <input type="text"
                            class="mt-1 w-full rounded-md border p-2"
                            wire:model.live="revision_actual"
                            placeholder="Ej. 00">
                        @error('revision_actual') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Título del nuevo documento</label>
                        <input type="text"
                            class="mt-1 w-full rounded-md border p-2"
                            wire:model.live="titulo"
                            placeholder="Nombre del documento">
                        @error('titulo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            @else
                {{-- MODO MODIFICACIÓN / BAJA: documento existente --}}
                <div class="grid md:grid-cols-2 gap-4" wire:key="rechazo-modificacion">
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
                        @error('documento_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Área</label>
                        <input type="text"
                            class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                            value="{{ $docSel?->area?->nombre }}"
                            readonly>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mt-4" wire:key="rechazo-modificacion-extra">
                    <div>
                        <label class="block text-sm font-medium">Revisión actual</label>
                        <input type="text"
                            class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                            value="{{ $docSel?->revision }}"
                            readonly>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Título</label>
                        <input type="text"
                            class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                            value="{{ $docSel?->nombre }}"
                            readonly>
                    </div>
                </div>
            @endif
        </div>

        {{-- Bloque: Tipo de trámite --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="text-sm font-semibold mb-3">TIPO DE TRÁMITE</div>
            <div class="max-w-sm">
                <label class="block text-sm font-medium">Selecciona el tipo</label>
                <select class="mt-1 w-full rounded-md border p-2" wire:model.live="tipo">
                    <option value="creacion">CREACIÓN</option>
                    <option value="modificacion">MODIFICACIÓN</option>
                    <option value="baja">BAJA</option>
                </select>
                @error('tipo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        @php use Illuminate\Support\Facades\Storage; @endphp

        {{-- Bloque: Descripción del cambio --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL CAMBIO</div>

            <div class="space-y-8">
                {{-- ========== DICE ========== --}}
                <div>
                    <label class="block text-sm font-medium">Dice</label>
                    <textarea rows="8" class="mt-1 w-full rounded-md border p-2"
                            wire:model.defer="cambio_dice"></textarea>
                    @error('cambio_dice') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                    {{-- Acciones de imágenes --}}
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
                                    {{-- Ícono imagen --}}
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

                        {{-- Previews NUEVAS (temporal) --}}
                        @if(!empty($imagenesDice))
                            <div class="mt-2 flex flex-wrap gap-3">
                                @foreach($imagenesDice as $idx => $img)
                                    <div class="relative">
                                        <img src="{{ $img->temporaryUrl() }}"
                                            class="h-24 w-24 object-cover rounded-md border dark:border-zinc-700">
                                        {{-- Quitar temporal --}}
                                        <button type="button" wire:click="removeDice({{ $idx }})"
                                                class="absolute -top-2 -right-2 inline-flex h-8 w-8 items-center justify-center rounded-full
                                                    border shadow-lg ring-1
                                                    bg-white text-red-600 border-red-500 ring-black/10
                                                    dark:bg-red-600 dark:text-white dark:border-red-600 dark:ring-zinc-900
                                                    hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-black/20 dark:focus:ring-white/30"
                                                aria-label="Quitar imagen">
                                            {{-- basurero --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M9 3a1 1 0 0 0-1 1v1H5.5a1 1 0 1 0 0 2H6v11a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V7h.5a1 1 0 1 0 0-2H16V4a1 1 0 0 0-1-1H9Zm2 2h2V5h-2V5Zm-3 4h8v10a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V9Zm2 2a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Zm4 0a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Galería EXISTENTE (desde BD) --}}
                        @if(!empty($adjuntosDice))
                            <div class="mt-3">
                                <div class="text-xs text-zinc-600 dark:text-zinc-400 mb-1">Imágenes existentes</div>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($adjuntosDice as $adj)
                                        @php $url = Storage::disk($adj->disk)->url($adj->path); @endphp
                                        <div class="relative">
                                            <img src="{{ $url }}" class="h-24 w-24 object-cover rounded-md border dark:border-zinc-700">
                                            {{-- Eliminar adjunto existente --}}
                                            <button type="button" wire:click="deleteAdjunto({{ $adj->id }})"
                                                    class="absolute -top-2 -right-2 inline-flex h-8 w-8 items-center justify-center rounded-full
                                                        border shadow-lg ring-1
                                                        bg-white text-red-600 border-red-500 ring-black/10
                                                        dark:bg-red-600 dark:text-white dark:border-red-600 dark:ring-zinc-900
                                                        hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-black/20 dark:focus:ring-white/30"
                                                    aria-label="Eliminar imagen">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M9 3a1 1 0 0 0-1 1v1H5.5a1 1 0 1 0 0 2H6v11a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V7h.5a1 1 0 1 0 0-2H16V4a1 1 0 0 0-1-1H9Zm2 2h2V5h-2V5Zm-3 4h8v10a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V9Zm2 2a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Zm4 0a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ========== DEBE DECIR ========== --}}
                <div>
                    <label class="block text-sm font-medium">Debe decir</label>
                    <textarea rows="8" class="mt-1 w-full rounded-md border p-2"
                            wire:model.defer="cambio_debe_decir"></textarea>
                    @error('cambio_debe_decir') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                    <div class="mt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                                Agregar imágenes (opcional)
                            </span>

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

                        {{-- Previews NUEVAS --}}
                        @if(!empty($imagenesDebeDecir))
                            <div class="mt-2 flex flex-wrap gap-3">
                                @foreach($imagenesDebeDecir as $idx => $img)
                                    <div class="relative">
                                        <img src="{{ $img->temporaryUrl() }}"
                                            class="h-24 w-24 object-cover rounded-md border dark:border-zinc-700">
                                        <button type="button" wire:click="removeDebeDecir({{ $idx }})"
                                                class="absolute -top-2 -right-2 inline-flex h-8 w-8 items-center justify-center rounded-full
                                                    border shadow-lg ring-1
                                                    bg-white text-red-600 border-red-500 ring-black/10
                                                    dark:bg-red-600 dark:text-white dark:border-red-600 dark:ring-zinc-900
                                                    hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-black/20 dark:focus:ring-white/30"
                                                aria-label="Quitar imagen">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M9 3a1 1 0 0 0-1 1v1H5.5a1 1 0 1 0 0 2H6v11a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V7h.5a1 1 0 1 0 0-2H16V4a1 1 0 0 0-1-1H9Zm2 2h2V5h-2V5Zm-3 4h8v10a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V9Zm2 2a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Zm4 0a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Galería EXISTENTE --}}
                        @if(!empty($adjuntosDebeDecir))
                            <div class="mt-3">
                                <div class="text-xs text-zinc-600 dark:text-zinc-400 mb-1">Imágenes existentes</div>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($adjuntosDebeDecir as $adj)
                                        @php $url = Storage::disk($adj->disk)->url($adj->path); @endphp
                                        <div class="relative">
                                            <img src="{{ $url }}" class="h-24 w-24 object-cover rounded-md border dark:border-zinc-700">
                                            <button type="button" wire:click="deleteAdjunto({{ $adj->id }})"
                                                    class="absolute -top-2 -right-2 inline-flex h-8 w-8 items-center justify-center rounded-full
                                                        border shadow-lg ring-1
                                                        bg-white text-red-600 border-red-500 ring-black/10
                                                        dark:bg-red-600 dark:text-white dark:border-red-600 dark:ring-zinc-900
                                                        hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-black/20 dark:focus:ring-white/30"
                                                    aria-label="Eliminar imagen">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M9 3a1 1 0 0 0-1 1v1H5.5a1 1 0 1 0 0 2H6v11a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V7h.5a1 1 0 1 0 0-2H16V4a1 1 0 0 0-1-1H9Zm2 2h2V5h-2V5Zm-3 4h8v10a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V9Zm2 2a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Zm4 0a1 1 0 1 1 2 0v6a1 1 0 1 1-2 0v-6Z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
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
            @error('justificacion') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Bloque: Requiere capacitación / Difusión --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm font-semibold mb-2">REQUIERE CAPACITACIÓN</div>
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" value="1" wire:model.live="requiere_capacitacion"> <span>SI</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" value="0" wire:model.live="requiere_capacitacion"> <span>NO</span>
                        </label>
                    </div>
                </div>

                <div>
                    <div class="text-sm font-semibold mb-2">DIFUSIÓN</div>
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" value="1" wire:model.live="requiere_difusion"> <span>SI</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" value="0" wire:model.live="requiere_difusion"> <span>NO</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enviar nuevamente (abre modal) --}}
        <div class="pt-4 flex items-center gap-3">
            <flux:button type="button"
                         wire:click="abrirConfirmarReenviar"
                         variant="primary"
                         class="!bg-blue-600 hover:!bg-blue-700 !text-white">
                Enviar nuevamente
            </flux:button>
            <span class="text-sm text-gray-600 dark:text-gray-400"
                  wire:loading.delay
                  wire:target="reenviar">Enviando…</span>
        </div>

        {{-- Modal de confirmación --}}
        @if($showConfirmReenviar)
            <flux:modal wire:model="showConfirmReenviar" title="Confirmar reenvío">
                <div class="space-y-3">
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">
                        ¿Deseas enviar nuevamente esta solicitud para revisión?
                    </p>

                    <div class="rounded-md border p-3 text-xs dark:border-zinc-700">
                        <div><span class="font-medium">Folio:</span> {{ $solicitud->folio }}</div>
                        <div>
                            <span class="font-medium">Documento:</span>
                            {{ $docSel?->codigo }} — {{ $docSel?->nombre }}
                        </div>
                    </div>

                    <div class="mt-2 flex justify-end gap-2">
                        <flux:button type="button" variant="ghost"
                                     wire:click="cerrarConfirmarReenviar">
                            Cancelar
                        </flux:button>

                        <flux:button type="button"
                                     wire:click="reenviar"
                                     wire:loading.attr="disabled"
                                     variant="primary"
                                     class="!bg-blue-600 hover:!bg-blue-700 !text-white">
                            Confirmar envío
                        </flux:button>
                    </div>
                </div>
            </flux:modal>
        @endif
    </div>
</section>
