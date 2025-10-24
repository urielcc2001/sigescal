<div class="space-y-4">

    {{-- Encabezado + Filtros (título centrado, buscador abajo) --}}
    <div class="space-y-3">

        {{-- Título centrado --}}
        <h2 class="text-xl font-semibold text-center">
            Revisión de quejas y sugerencias
        </h2>

        {{-- Buscador debajo del título (centrado, ancho máximo) --}}
        <div class="mx-auto w-full max-w-3xl">
            <flux:input
                placeholder="Buscar (folio, No. control, estado, tipo)…"
                wire:model.live.debounce.400ms="search"
                class="w-full"
            />
        </div>

        {{-- Filtros en horizontal (en móvil se apilan) --}}
        <div class="grid grid-cols-1 gap-2 md:grid-cols-3">
            <div class="w-full">
                <flux:select wire:model.live="fTipo" class="w-full">
                    <option value="">Tipo (todos)</option>
                    <option value="queja">Queja</option>
                    <option value="sugerencia">Sugerencia</option>
                </flux:select>
            </div>

            <div class="w-full">
                <flux:select wire:model.live="fEstado" class="w-full">
                    <option value="">Estado (todos)</option>
                    <option value="abierta">Abierta</option>
                    <option value="en_proceso">En proceso</option>
                    <option value="respondida">Respondida</option>
                    <option value="cerrada">Cerrada</option>
                </flux:select>
            </div>

            <div class="w-full">
                <flux:select wire:model.live="perPage" class="w-full">
                    <option value="10">10 / pág.</option>
                    <option value="20">20 / pág.</option>
                    <option value="50">50 / pág.</option>
                </flux:select>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto rounded-xl border bg-white dark:bg-neutral-900">
        <table class="min-w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-800/60">
                <tr class="text-left">
                    <th class="px-4 py-3 font-medium">Folio</th>
                    <th class="px-4 py-3 font-medium">Tipo</th>
                    <th class="px-4 py-3 font-medium">No. control</th>
                    <th class="px-4 py-3 font-medium">Estado</th>
                    <th class="px-4 py-3 font-medium">Fecha</th>
                    <th class="px-4 py-3 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-neutral-800">
                @forelse($rows as $row)
                    @php
                        $pill = match($row->estado) {
                            'abierta'    => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
                            'en_proceso' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                            'respondida' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200',
                            'cerrada'    => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
                            default      => 'bg-neutral-100 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200',
                        };
                    @endphp
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $row->folio }}</td>
                        <td class="px-4 py-3 capitalize">{{ $row->tipo }}</td>
                        <td class="px-4 py-3">{{ $row->student?->numcontrol }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs {{ $pill }}">
                                {{ str_replace('_',' ',$row->estado) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ optional($row->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                @if($row->estado === 'abierta')
                                    <flux:button size="sm" variant="outline" icon="bolt"
                                        wire:click="markInProcess({{ $row->id }})">
                                        En proceso
                                    </flux:button>
                                @endif

                                <flux:button size="sm" variant="outline" icon="eye"
                                    wire:click="view({{ $row->id }})">
                                    Ver / responder
                                </flux:button>

                                @if($row->estado !== 'cerrada')
                                    <flux:button size="sm" variant="ghost" icon="check"
                                        wire:click="closeTicket({{ $row->id }})">
                                        Cerrar
                                    </flux:button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-neutral-500" colspan="6">
                            No hay registros.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3">
            {{ $rows->links() }}
        </div>
    </div>

    {{-- Modal Ver / Responder --}}
    <flux:modal wire:model="showView" title="Detalle y respuesta" icon="message-square" size="lg">
        @php
            $selected = $this->selected;
            $s = $selected?->student;
        @endphp

        @if($selected)
            <div class="space-y-6 text-sm">

                {{-- 1) Datos del estudiante --}}
                <div>
                    <div class="mb-2 text-xs font-semibold uppercase text-neutral-500">
                        Datos del estudiante
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <div class="text-neutral-500">Nombre</div>
                            <div class="font-medium">{{ $s?->nombre ?? '—' }}</div>
                        </div>
                        {{-- 
                        <div>
                            <div class="text-neutral-500">Correo</div>
                            <div class="font-medium break-all">{{ $s?->email ?? '—' }}</div>
                        </div>
                        --}}
                        <div>
                            <div class="text-neutral-500">No. de control</div>
                            <div class="font-medium">{{ $s?->numcontrol ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Semestre</div>
                            <div class="font-medium">{{ $s?->semestre ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Carrera</div>
                            <div class="font-medium">{{ $s?->carrera_code ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Grupo</div>
                            <div class="font-medium">{{ $s?->grupo ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Turno</div>
                            <div class="font-medium">{{ $s?->turno ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Aula</div>
                            <div class="font-medium">{{ $s?->aula ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Teléfono</div>
                            <div class="font-medium">{{ $s?->telefono ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- 2) Detalles de la solicitud --}}
                <div>
                    <div class="mb-2 text-xs font-semibold uppercase text-neutral-500">
                        Detalles de la solicitud
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <div class="text-neutral-500">Folio</div>
                            <div class="font-medium font-mono">{{ $selected->folio }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Tipo</div>
                            <div class="font-medium capitalize">{{ $selected->tipo }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Estado</div>
                            <div class="font-medium">{{ str_replace('_',' ',$selected->estado) }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Fecha de creación</div>
                            <div class="font-medium">{{ optional($selected->created_at)->format('d/m/Y') }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">Respondida</div>
                            <div class="font-medium">
                                {{ $selected->respondida_at?->format('d/m/Y') ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3) Descripción --}}
                <div>
                    <div class="mb-1 text-neutral-500">Descripción</div>
                    <div class="rounded-md border p-3 dark:border-neutral-700 whitespace-pre-line">
                        {{ $selected->descripcion }}
                    </div>
                </div>

                {{-- 4) Respuesta actual / nueva respuesta --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <div class="mb-1 text-neutral-500">Respuesta actual</div>
                        <div class="rounded-md border p-3 dark:border-neutral-700 min-h-20 whitespace-pre-line">
                            {{ $selected->respuesta ?: 'Sin respuesta.' }}
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 text-neutral-500">Nueva respuesta</div>
                        <flux:textarea rows="5" placeholder="Escribe la respuesta para el estudiante…"
                            wire:model.live="respuestaText" />
                        @error('respuestaText')
                            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 5) Acciones --}}
                <div class="flex justify-end gap-2">
                    <flux:button variant="ghost" @click="$wire.closeView()">Cerrar</flux:button>

                    <flux:button
                        variant="primary"
                        icon="check"
                        wire:click="respond"
                        wire:loading.attr="disabled"
                        wire:target="respond"
                        class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                    >
                        Enviar respuesta
                    </flux:button>
                    <flux:button
                        variant="primary"
                        icon="arrow-down-tray"
                        href="{{ route('calidad.quejas.pdf.ver', $selected) }}"
                        target="_blank">
                        Descargar formato
                    </flux:button>
                </div>
            </div>
        @endif
    </flux:modal>

</div>
