<div class="space-y-4">

    {{-- Encabezado / Filtros --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold">Mis quejas y sugerencias</h2>

        <div class="flex items-center gap-2">
            <flux:input
                placeholder="Buscar por folio, tipo, estado…"
                wire:model.live.debounce.400ms="search"
                class="w-64"
            />
            <flux:select wire:model.live="perPage" class="w-28">
                <option value="5">5 / pág.</option>
                <option value="10">10 / pág.</option>
                <option value="20">20 / pág.</option>
                <option value="50">50 / pág.</option>
            </flux:select>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto rounded-xl border bg-white dark:bg-neutral-900">
        <table class="min-w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-800/60">
                <tr class="text-left">
                    <th class="px-4 py-3 font-medium">Folio</th>
                    <th class="px-4 py-3 font-medium">Tipo</th>
                    <th class="px-4 py-3 font-medium">Estado</th>
                    <th class="px-4 py-3 font-medium">Fecha</th>
                    <th class="px-4 py-3 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-neutral-800">
                @forelse($rows as $row)
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $row->folio }}</td>
                        <td class="px-4 py-3 capitalize">
                            {{ $row->tipo }} {{-- queja / sugerencia --}}
                        </td>
                        <td class="px-4 py-3">
                            {{-- Pill de estado simple --}}
                            @php
                                $color = match($row->estado) {
                                    'abierta'      => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
                                    'en_proceso'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                                    'cerrada'      => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
                                    default        => 'bg-neutral-100 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs {{ $color }}">
                                {{ str_replace('_',' ',$row->estado) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            {{ optional($row->created_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end">
                                <flux:button
                                    variant="outline"
                                    size="sm"
                                    icon="eye"
                                    wire:click="view({{ $row->id }})"
                                >
                                    Ver
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-neutral-500" colspan="5">
                            No hay registros todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        <div class="px-4 py-3">
            {{ $rows->links() }}
        </div>
    </div>

    {{-- Modal Ver Detalle --}}
    <flux:modal
        wire:model="showView"
        title="Detalle de la solicitud"
        icon="document-text"
        size="lg"
    >
        @php $selected = $this->selected; @endphp

        @if($selected)
            <div class="space-y-4 text-sm">
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <div class="text-neutral-500">Folio</div>
                        <div class="font-medium font-mono">{{ $selected->folio }}</div>
                    </div>
                    <div>
                        <div class="text-neutral-500">Fecha</div>
                        <div class="font-medium">{{ optional($selected->created_at)->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <div class="text-neutral-500">Tipo</div>
                        <div class="font-medium capitalize">{{ $selected->tipo }}</div>
                    </div>
                    <div>
                        <div class="text-neutral-500">Estado</div>
                        @php
                            $color = match($selected->estado) {
                                'abierta'      => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
                                'en_proceso'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                                'respondida'   => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200',
                                'cerrada'      => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
                                default        => 'bg-neutral-100 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200',
                            };
                        @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs {{ $color }}">
                            {{ str_replace('_',' ',$selected->estado) }}
                        </span>
                    </div>
                </div>

                <div>
                    <div class="mb-1 text-neutral-500">Descripción</div>
                    <div class="rounded-md border p-3 dark:border-neutral-700 whitespace-pre-line">
                        {{ $selected->descripcion }}
                    </div>
                </div>

                <div>
                    <div class="mb-1 text-neutral-500">Respuesta</div>
                    @if($selected->respuesta)
                        <div class="rounded-md border p-3 dark:border-neutral-700 whitespace-pre-line">
                            {{ $selected->respuesta }}
                        </div>
                        @if($selected->respondida_at)
                            <div class="mt-1 text-xs text-neutral-500">
                                Respondida el {{ $selected->respondida_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    @else
                        <div class="text-neutral-500">Aún no hay respuesta.</div>
                    @endif
                </div>

                <div class="flex justify-end">
                    <flux:button variant="ghost" @click="$wire.closeView()">Cerrar</flux:button>
                </div>
            </div>
        @else
            <div class="text-neutral-500">No se encontró el registro seleccionado.</div>
        @endif
    </flux:modal>

</div>
