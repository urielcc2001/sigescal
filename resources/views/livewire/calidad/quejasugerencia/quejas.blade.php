<div class="space-y-6">

    {{-- Encabezado institucional --}}
    <div class="text-center space-y-1">
        <p class="text-[11px] tracking-widest text-zinc-600 dark:text-zinc-400">
            SUBDIRECCIÓN PLANEACIÓN Y VINCULACIÓN
        </p>
        <p class="text-[11px] tracking-widest text-zinc-600 dark:text-zinc-400">
            COORDINACIÓN DE CALIDAD
        </p>

        <flux:heading size="xl" class="mt-3 uppercase">
            Formato para Quejas o Sugerencias
        </flux:heading>
        <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
            Procedimiento para la Atención de Quejas y Sugerencias
        </p>

        <div class="mt-2 inline-flex items-center gap-3 text-xs text-zinc-600 dark:text-zinc-300">
            <span
                class="inline-flex items-center gap-1 rounded-md border border-zinc-300/70 bg-white/60 px-2 py-0.5
                       dark:border-zinc-700 dark:bg-zinc-900/40">
                <span class="font-medium">Código:</span> ITTUX-CA-PO-001-01
            </span>
            <span
                class="inline-flex items-center gap-1 rounded-md border border-zinc-300/70 bg-white/60 px-2 py-0.5
                       dark:border-zinc-700 dark:bg-zinc-900/40">
                <span class="font-medium">Revisión:</span> 2
            </span>
        </div>
    </div>

    {{-- Bloque: Folio y Fecha --}}
    <div
        class="rounded-md border bg-white/60 p-4
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
    <div
        class="rounded-md border bg-white/60 p-4
               border-zinc-200 dark:bg-zinc-900/50 dark:border-zinc-700">
        <div class="text-sm font-semibold mb-3 text-zinc-700 dark:text-zinc-300">
            DATOS DEL ALUMNO
            <p class="mt-1 text-xs font-normal text-zinc-500 dark:text-zinc-400">
                Si ya has registrado una queja antes, escribe primero tu número de control y se rellenarán tus datos automáticamente.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            {{-- No. de Control (PRIMERO) --}}
            <div>
                <flux:label>No. de Control</flux:label>
                <flux:input
                    wire:model.lazy="numcontrol"
                    placeholder="Ej. 221200000"
                />
                @error('numcontrol') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nombre --}}
            <div>
                <flux:label>Nombre</flux:label>
                <flux:input wire:model.defer="nombre" placeholder="Nombre completo" />
                @error('nombre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Correo institucional --}}
            <div>
                <flux:label>Correo institucional</flux:label>
                <flux:input type="email" wire:model.defer="email" placeholder="correo@ittux.edu.mx" />
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Teléfono --}}
            <div>
                <flux:label>Teléfono</flux:label>
                <flux:input wire:model.defer="telefono" placeholder="Tu número de contacto" />
                @error('telefono') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Carrera --}}
            <div>
                <flux:label>Carrera</flux:label>
                <flux:select wire:model.defer="carrera_code" class="w-full">
                    <option value="">Seleccione una opción</option>
                    <option value="LAOK">Licenciatura en Administración (LAOK)</option>
                    <option value="LCPOK">Licenciatura en Contador Público (LCPOK)</option>
                    <option value="IBQOK">Ingeniería Bioquímica (IBQOK)</option>
                    <option value="ICOK">Ingeniería Civil (ICOK)</option>
                    <option value="IEOK">Ingeniería Electrónica (IEOK)</option>
                    <option value="IEMOK">Ingeniería Electromecánica (IEMOK)</option>
                    <option value="IIOK">Ingeniería Informática (IIOK)</option>
                    <option value="IGEOK">Ingeniería en Gestión Empresarial (IGEOK)</option>
                    <option value="ISCOK">Ingeniería en Sistemas Computacionales (ISCOK)</option>
                    <option value="IDAOK">Ingeniería en Desarrollo de Aplicaciones (IDAOK)</option>
                </flux:select>
                @error('carrera_code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Semestre --}}
            <div>
                <flux:label>Semestre</flux:label>
                <flux:select wire:model.defer="semestre" class="w-full">
                    <option value="">Seleccione semestre</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </flux:select>
                @error('semestre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Grupo --}}
            <div>
                <flux:label>Grupo</flux:label>
                <flux:select wire:model.defer="grupo" class="w-full">
                    <option value="">Seleccione grupo</option>
                    @foreach (['A','B','C','D','E'] as $g)
                        <option value="{{ $g }}">{{ $g }}</option>
                    @endforeach
                </flux:select>
                @error('grupo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Turno --}}
            <div>
                <flux:label>Turno</flux:label>
                <flux:select wire:model.defer="turno" class="w-full">
                    <option value="">Seleccione turno</option>
                    <option value="matutino">Matutino</option>
                    <option value="vespertino">Vespertino</option>
                    <option value="sabatino">Sabatino</option>
                </flux:select>
                @error('turno') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Aula --}}
            <div class="md:col-span-2">
                <flux:label>Aula</flux:label>
                <flux:input wire:model.defer="aula" placeholder="" />
                @error('aula') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

    </div>

    {{-- Bloque: Tipo y redacción --}}
    <div
        class="rounded-md border bg-white/60 p-4
               border-zinc-200 dark:bg-zinc-900/50 dark:border-zinc-700">

        {{-- Tipo --}}
        <div>
            <flux:label>Tipo</flux:label>
            <div class="mt-2 mb-4 flex flex-wrap items-center gap-6">
                <label class="inline-flex items-center gap-2">
                    <input
                        type="radio"
                        class="rounded border-zinc-300 dark:border-zinc-600"
                        value="queja"
                        wire:model="tipo"
                    >
                    <span class="text-sm">Queja</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input
                        type="radio"
                        class="rounded border-zinc-300 dark:border-zinc-600"
                        value="sugerencia"
                        wire:model="tipo"
                    >
                    <span class="text-sm">Sugerencia</span>
                </label>
            </div>
            @error('tipo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Descripción --}}
        <div class="space-y-1.5">
            <flux:label>Describa su queja/sugerencia</flux:label>
            <textarea
                rows="10"
                wire:model.defer="descripcion"
                class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm
                       min-h-[12rem] resize-y leading-relaxed
                       dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                placeholder="Describe de forma clara, objetiva y, si aplica, indica lugar/fecha/personas involucradas."
            ></textarea>
            @error('descripcion') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Botón centrado que abre el modal --}}
    <div class="flex justify-center pt-6">
        <flux:button
            variant="primary"
            icon="paper-airplane"
            wire:click="$set('showConfirm', true)"
            wire:loading.attr="disabled"
        >
            Enviar solicitud
        </flux:button>
    </div>

    {{-- Modal de confirmación --}}
    <flux:modal
        wire:model="showConfirm"
        title="Confirmar envío"
        icon="question-mark-circle"
        size="md"
    >
        <div class="space-y-3">
            <p class="text-sm text-zinc-600 dark:text-zinc-300">
                ¿Deseas enviar la {{ $tipo === 'sugerencia' ? 'sugerencia' : 'queja' }} ahora?
            </p>

            <div class="rounded-md border p-3 text-xs dark:border-zinc-700 max-h-48 overflow-auto space-y-1">
                <div><span class="font-medium">Nombre:</span> {{ $nombre }}</div>
                <div><span class="font-medium">No. Control:</span> {{ $numcontrol }}</div>
                <div><span class="font-medium">Carrera:</span> {{ $carrera_code }}</div>
                <div class="mt-2">
                    <span class="font-medium">Descripción:</span>
                    <div class="mt-0.5 whitespace-pre-line">
                        {{ $descripcion }}
                    </div>
                </div>
                @if($telefono)
                    <div class="mt-1"><span class="font-medium">Teléfono:</span> {{ $telefono }}</div>
                @endif
            </div>

            <div class="mt-2 flex justify-end gap-2">
                <flux:button
                    type="button"
                    variant="ghost"
                    wire:click="$set('showConfirm', false)"
                >
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

    {{-- Mensaje de éxito + botón imprimir --}}
    @if($enviada)
        <div
            id="folio-print"
            class="mt-6 rounded-md border border-green-300/70 bg-green-50 p-4 text-green-900
                   dark:border-green-700 dark:bg-green-900/20 dark:text-green-200 space-y-2">
            <p class="font-medium">¡Tu registro fue enviado!</p>
            <p class="text-sm">
                @if($folio)
                    <span class="font-medium">FOLIO:</span> {{ $folio }} —
                @endif
                Conserva el folio para referencia. Con él podrás consultar el estado de tu queja o sugerencia.
            </p>

            <div class="flex flex-wrap gap-2">
                <flux:button
                    size="sm"
                    variant="outline"
                    icon="printer"
                    x-on:click="window.print()"
                >
                    Imprimir folio
                </flux:button>

                @if(Route::has('quejas.estado-publico'))
                    <a href="{{ route('quejas.estado-publico') }}"
                       class="inline-flex items-center text-xs font-medium text-blue-700 hover:underline">
                        Consultar estado de mi queja
                    </a>
                @endif
            </div>
        </div>
    @endif

    <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-4">
        ¿Ya registraste una queja y tienes tu folio?
        <a href="{{ route('quejas.estado-publico') }}"
        class="font-medium text-blue-700 hover:underline">
            Consulta aquí el estado de tu queja o sugerencia.
        </a>
    </p>


    {{-- Nota --}}
    <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-4">
        Para validar su queja y/o sugerencia deberá verificar su dato de contacto.
        Esta información es de carácter confidencial y <strong>su participación es anónima.</strong>
    </p>

    {{-- CSS para impresión SOLO del folio --}}
    <style>
    @media print {
        /* Dentro de este componente: ocultar todo menos el bloque del folio */
        .space-y-6 > *:not(#folio-print) {
            display: none !important;
        }

        /* Ajustar el bloque del folio para impresión */
        #folio-print {
            margin: 2cm auto 0 auto;
            page-break-inside: avoid;
        }
    }
    </style>
</div>
