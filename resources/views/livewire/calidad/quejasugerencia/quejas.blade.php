<div class="mx-auto max-w-4xl space-y-6">

    {{-- Encabezado institucional --}}
    <div class="text-center space-y-1">
        <p class="text-[11px] tracking-widest text-zinc-600 dark:text-zinc-400">SUBDIRECCIÓN PLANEACIÓN Y VINCULACIÓN</p>
        <p class="text-[11px] tracking-widest text-zinc-600 dark:text-zinc-400">COORDINACIÓN DE CALIDAD</p>

        <flux:heading size="xl" class="mt-3 uppercase">Formato para Quejas o Sugerencias</flux:heading>
        <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Procedimiento para la Atención de Quejas y Sugerencias</p>

        <div class="mt-2 inline-flex items-center gap-3 text-xs text-zinc-600 dark:text-zinc-300">
            <span class="inline-flex items-center gap-1 rounded-md border border-zinc-300/70 bg-white/60 px-2 py-0.5
                         dark:border-zinc-700 dark:bg-zinc-900/40">
                <span class="font-medium">Código:</span> ITTUX-CA-PO-001-01
            </span>
            <span class="inline-flex items-center gap-1 rounded-md border border-zinc-300/70 bg-white/60 px-2 py-0.5
                         dark:border-zinc-700 dark:bg-zinc-900/40">
                <span class="font-medium">Revisión:</span> 2
            </span>
        </div>
    </div>

    {{-- Aviso de envío --}}
    @if($enviada)
        <div class="rounded-md border border-green-300/70 bg-green-50 p-4 text-green-900
                    dark:border-green-700 dark:bg-green-900/20 dark:text-green-200">
            <p class="font-medium">¡Tu registro fue enviado!</p>
            <p class="text-sm mt-0.5">
                @if($folio)
                    <span class="font-medium">FOLIO:</span> {{ $folio }} —
                @endif
                Conserva el folio para referencia. Recibirás respuesta por este medio.
            </p>
        </div>
    @endif

    {{-- Bloque: Folio y Fecha --}}
    <div class="rounded-md border bg-white/60 p-4
                border-zinc-200 dark:bg-zinc-900/50 dark:border-zinc-700">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <flux:label>Folio</flux:label>
                <flux:input value="{{ $folio ?? '—' }}" disabled />
            </div>
            <div>
                <flux:label>Fecha</flux:label>
                <flux:input type="date" value="{{ $fecha }}" disabled />
            </div>
        </div>
    </div>

    {{-- Bloque: Datos del alumno --}}
    <div class="rounded-md border bg-white/60 p-4
                border-zinc-200 dark:bg-zinc-900/50 dark:border-zinc-700">
        <div class="text-sm font-semibold mb-3 text-zinc-700 dark:text-zinc-300">DATOS DEL ALUMNO</div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <flux:label>Nombre</flux:label>
                <flux:input value="{{ $nombre }}" disabled />
            </div>
            <div>
                <flux:label>Correo institucional</flux:label>
                <flux:input type="email" value="{{ $email }}" disabled />
            </div>

            <div>
                <flux:label>Teléfono (editable)</flux:label>
                <flux:input wire:model.lazy="telefono" placeholder="Tu número de contacto" />
                @error('telefono') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <flux:label>No. de Control</flux:label>
                <flux:input value="{{ $numcontrol }}" disabled />
            </div>

            <div>
                <flux:label>Carrera</flux:label>
                <flux:input value="{{ $carrera_code }}" disabled />
            </div>
            <div>
                <flux:label>Semestre</flux:label>
                <flux:input value="{{ $semestre }}" disabled />
            </div>

            <div>
                <flux:label>Grupo</flux:label>
                <flux:input value="{{ $grupo }}" disabled />
            </div>
            <div>
                <flux:label>Turno</flux:label>
                <flux:input value="{{ $turno }}" disabled />
            </div>

            <div class="md:col-span-2">
                <flux:label>Aula</flux:label>
                <flux:input value="{{ $aula }}" disabled />
            </div>
        </div>
    </div>

    {{-- Bloque: Tipo y redacción --}}
    <div class="rounded-md border bg-white/60 p-4
                border-zinc-200 dark:bg-zinc-900/50 dark:border-zinc-700">

        {{-- Tipo --}}
        <div>
            <flux:label>Tipo</flux:label>
            <div class="mt-2 mb-4 flex flex-wrap items-center gap-6">
                <label class="inline-flex items-center gap-2">
                    <input type="radio" class="rounded border-zinc-300 dark:border-zinc-600"
                        value="queja" wire:model="tipo">
                    <span class="text-sm">Queja</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" class="rounded border-zinc-300 dark:border-zinc-600"
                        value="sugerencia" wire:model="tipo">
                    <span class="text-sm">Sugerencia</span>
                </label>
            </div>
            @error('tipo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Descripción (ancha y alta) --}}
        <div class="space-y-1.5">
            <flux:label>Describa su queja/sugerencia</flux:label>
            <textarea
                rows="10"
                wire:model.defer="descripcion"
                class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm
                    min-h-[12rem] resize-y leading-relaxed
                    dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                placeholder="Describe de forma clara, objetiva y, si aplica, indica lugar/fecha/personas involucradas.">
            </textarea>
            @error('descripcion') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
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
                ¿Deseas enviar la {{ $tipo === 'sugerencia' ? 'sugerencia' : 'queja' }} ahora?
            </p>

            {{-- Vista previa opcional --}}
            <div class="rounded-md border p-3 text-xs dark:border-zinc-700 max-h-48 overflow-auto">
                <div class="mb-2"><span class="font-medium">Descripción:</span> {{ $descripcion }}</div>
                @if($telefono)
                    <div><span class="font-medium">Teléfono:</span> {{ $telefono }}</div>
                @endif
            </div>

            <div class="mt-2 flex justify-end gap-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showConfirm', false)">
                    Cancelar
                </flux:button>

                <flux:button
                    type="button"
                    variant="primary"
                    icon="check-circle"
                    wire:click="confirmSubmit"
                    wire:loading.attr="disabled"
                    class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                >
                    Sí, enviar
                </flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Nota --}}
    <p class="text-xs text-zinc-600 dark:text-zinc-400">
        Para validar su queja y/o sugerencia deberá proporcionar un dato de contacto. Esta información es de carácter confidencial.
    </p>
</div>
