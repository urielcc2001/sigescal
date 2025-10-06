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
                    @error('documento_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Área</label>
                    <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                           value="{{ $docSel?->area?->nombre }}"
                           readonly>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium">Revisión actual</label>
                    <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                           value="{{ $docSel?->revision }}"
                           readonly>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Título</label>
                    <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                           value="{{ $docSel?->nombre }}"
                           readonly>
                </div>
            </div>
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

        {{-- Bloque: Descripción del cambio --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL CAMBIO</div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Dice</label>
                    <textarea rows="8" class="mt-1 w-full rounded-md border p-2"
                              wire:model.defer="cambio_dice"></textarea>
                    @error('cambio_dice') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Debe decir</label>
                    <textarea rows="8" class="mt-1 w-full rounded-md border p-2"
                              wire:model.defer="cambio_debe_decir"></textarea>
                    @error('cambio_debe_decir') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
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
