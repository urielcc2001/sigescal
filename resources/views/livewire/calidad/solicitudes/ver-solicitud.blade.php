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
                <label class="block text-sm font-medium">Tipo seleccionado</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 capitalize"
                       value="{{ $solicitud->tipo }}" readonly>
            </div>
        </div>

        {{-- Bloque: Descripción del cambio --}}
        <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
            <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL CAMBIO</div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Dice</label>
                    <textarea rows="8" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                              readonly>{{ $solicitud->cambio_dice }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium">Debe decir</label>
                    <textarea rows="8" class="mt-1 w-full rounded-md border p-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                              readonly>{{ $solicitud->cambio_debe_decir }}</textarea>
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

        {{-- Botones de acción --}}
        @if($solicitud->estado === 'en_revision')
        <div class="pt-4 flex flex-wrap gap-3 sm:justify-center">
            <flux:button
            wire:click="abrirAprobar"
            icon="check"                 {{-- este sí existe --}}
            variant="primary"
            class="!bg-emerald-600 hover:!bg-emerald-700 !text-white">
            Aprobar
            </flux:button>

            <flux:button
            wire:click="abrirRechazar"
            icon="x-mark"                {{-- en lugar de "x" --}}
            variant="primary"
            class="!bg-red-600 hover:!bg-red-700 !text-white">
            Rechazar
            </flux:button>
        </div>
        @endif
    </div>

    {{-- Modal de aprobación --}}
    @if($showApproveModal)
    <flux:modal wire:model="showApproveModal" title="Confirmar aprobación">
        <div class="space-y-3">
        <p class="text-sm text-zinc-600 dark:text-zinc-300">Comentario (opcional) para el historial.</p>
        <flux:textarea wire:model.live="comentarioAprobacion" rows="3" placeholder="Comentario opcional..." />

        <div class="mt-2 flex justify-end gap-2">
            <flux:button variant="ghost" wire:click="$set('showApproveModal', false)">Cancelar</flux:button>
            <flux:button variant="primary" class="!bg-emerald-600 hover:!bg-emerald-700 !text-white"
                        wire:click="aprobar">Aprobar</flux:button>
        </div>
        </div>
    </flux:modal>
    @endif

    @if($showRejectModal)
    <flux:modal wire:model="showRejectModal" title="Confirmar rechazo">
        <div class="space-y-3">
        <p class="text-sm text-zinc-600 dark:text-zinc-300">Escribe el motivo del rechazo (requerido).</p>
        <flux:textarea wire:model.live="motivoRechazo" rows="4" placeholder="Motivo del rechazo..." />
        @error('motivoRechazo') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror

        <div class="mt-2 flex justify-end gap-2">
            <flux:button variant="ghost" wire:click="$set('showRejectModal', false)">Cancelar</flux:button>
            <flux:button variant="primary" class="!bg-red-600 hover:!bg-red-700 !text-white"
                        wire:click="rechazar">Rechazar</flux:button>
        </div>
        </div>
    </flux:modal>
    @endif
</section>
