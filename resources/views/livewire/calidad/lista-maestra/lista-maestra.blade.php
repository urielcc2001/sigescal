<section class="w-full">
    <x-page-heading>
        <x-slot:title>Lista maestra de documentos</x-slot:title>
        <x-slot:subtitle>Catálogo de documentos controlados</x-slot:subtitle>
    </x-page-heading>

    {{-- Filtros --}}
    <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
        {{-- Buscador --}}
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Buscar
            </label>
            <div class="mt-1 relative">
                <input
                    type="text"
                    placeholder="Buscar por código o título…"
                    wire:model.live.debounce.500ms="search"
                    class="w-full rounded-md border border-zinc-300 bg-white p-2 pr-9 text-sm
                           dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                />
                @if($search !== '')
                    <button
                        type="button"
                        wire:click="$set('search','')"
                        class="absolute inset-y-0 right-2 my-auto h-6 w-6 rounded-md text-zinc-500 hover:text-zinc-800
                               dark:text-zinc-400 dark:hover:text-zinc-200"
                        aria-label="Limpiar búsqueda">×</button>
                @endif
            </div>
        </div>

        {{-- Filtro por Área --}}
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Filtrar por área
            </label>
            <select
                wire:model.live="areaId"
                class="mt-1 w-full rounded-md border border-zinc-300 bg-white p-2 text-sm
                       dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                <option value="">Todas las áreas</option>
                @foreach($areas as $a)
                    <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                @endforeach
            </select>
            @can('lista-maestra.export')
                <flux:button
                    icon="document-arrow-down"
                    variant="outline"
                    @click="$wire.openExportModal()">
                    Descargar PDF
                </flux:button>
                <flux:modal wire:model="showExportModal" wire:key="export-lm-modal">
                    <div class="space-y-4">
                        <flux:heading size="lg">Fecha para el reporte</flux:heading>

                        <div>
                            <flux:label>Fecha de autorización</flux:label>
                            <input
                                type="date"
                                wire:model.live="exportDate"
                                class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-zinc-400
                                    dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-100"
                                min="1900-01-01" max="2100-12-31"
                            />
                            @error('exportDate') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-zinc-500 mt-1">
                                Por defecto tomamos la fecha más reciente de los documentos filtrados.
                            </p>
                        </div>

                        <div class="flex justify-end gap-2">
                            <flux:button variant="outline" @click="$wire.showExportModal = false">Cancelar</flux:button>
                            <flux:button variant="primary" wire:click="exportPdf" wire:loading.attr="disabled">
                                <span wire:loading.remove>Descargar</span>
                                <span wire:loading>Preparando…</span>
                            </flux:button>
                        </div>
                    </div>
                </flux:modal>
            @else
                <flux:button
                    icon="lock-closed"
                    variant="outline"
                    disabled
                    title="No tienes permiso para exportar la Lista Maestra">
                    Descargar PDF
                </flux:button>
            @endcan
        </div>
    </div>

    {{-- Tabla --}}
    <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm
                dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider">Código</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider">Título</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider">Revisión</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider w-28">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($docs as $row)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40" wire:key="doc-{{ $row->id }}">
                        <td class="px-4 py-2 text-sm font-medium">{{ $row->codigo }}</td>
                        <td class="px-4 py-2 text-sm">{{ $row->nombre }}</td>
                        <td class="px-4 py-2 text-sm">{{ $row->revision }}</td>
                        <td class="px-4 py-2 text-sm">
                            @if($row->fecha_autorizacion)
                                {{ \Illuminate\Support\Carbon::parse($row->fecha_autorizacion)->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm">
                            <div class="flex items-center gap-1">
                                {{-- Editar --}}
                                @can('lista-maestra.edit')
                                    <flux:button
                                        size="xs"
                                        variant="ghost"
                                        icon="pencil-square"
                                        wire:click="openEdit({{ $row->id }})"
                                        title="Editar">
                                        Editar
                                    </flux:button>
                                @elsecan('lista-maestra.view')
                                    <flux:button
                                        size="xs"
                                        variant="outline"
                                        icon="lock-closed"
                                        disabled
                                        title="No tienes permiso para editar en la Lista Maestra">
                                        Editar
                                    </flux:button>
                                @endcan

                                {{-- Eliminar --}}
                                @can('lista-maestra.delete')
                                    <flux:button
                                        size="xs"
                                        variant="ghost"
                                        icon="trash"
                                        wire:click="confirmDelete({{ $row->id }})"
                                        title="Eliminar">
                                        Eliminar
                                    </flux:button>
                                @elsecan('lista-maestra.view')
                                    <flux:button
                                        size="xs"
                                        variant="outline"
                                        icon="lock-closed"
                                        disabled
                                        title="No tienes permiso para eliminar en la Lista Maestra">
                                        Eliminar
                                    </flux:button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            No hay documentos para mostrar.
                        </td>
                    </tr>
                @endforelse
                </tbody>
        </table>
    </div>

    {{-- Modal: Editar documento --}}
    <flux:modal wire:model="showEditModal" title="Editar documento" icon="pencil-square" size="lg">
        <div class="space-y-6 text-sm">
            {{-- Encabezado contextual --}}
            <div class="rounded-md border p-3 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900/40">
                <div class="text-xs font-semibold uppercase text-neutral-500">Información del documento</div>
                <div class="mt-1 text-neutral-700 dark:text-neutral-300">
                    Ajusta los campos y guarda los cambios. Los valores se validan antes de actualizar.
                </div>
            </div>

            {{-- Formulario --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <flux:label>Código</flux:label>
                    <flux:input
                        placeholder="ITTUX-CA-PO-001-01"
                        wire:model.defer="codigo"
                        class="w-full"
                    />
                    @error('codigo') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                </div>

                <div>
                    <flux:label>Revisión</flux:label>
                    <flux:input
                        placeholder="Rev. 2"
                        wire:model.defer="revision"
                        class="w-full"
                    />
                    @error('revision') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                </div>

                <div class="sm:col-span-2">
                    <flux:label>Título</flux:label>
                    <flux:input
                        placeholder="Nombre del documento"
                        wire:model.defer="nombre"
                        class="w-full"
                    />
                    @error('nombre') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                </div>

                <div>
                    <flux:label>Fecha de autorización</flux:label>
                    <input
                        type="date"
                        wire:model.defer="fecha_autorizacion"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm
                            focus:outline-none focus:ring-2 focus:ring-zinc-400
                            dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-100"
                        min="1900-01-01" max="2100-12-31"
                    />
                    @error('fecha_autorizacion') <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Acciones (dentro del body, no en slot footer) --}}
            <div class="flex justify-end gap-2 pt-2">
                <flux:button variant="ghost" @click="$wire.showEditModal=false">Cancelar</flux:button>

                <flux:button
                    variant="primary"
                    icon="check"
                    wire:click="saveEdit"
                    wire:target="saveEdit"
                    wire:loading.attr="disabled"
                    class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                >
                    <span wire:loading.remove>Guardar cambios</span>
                    <span wire:loading>Guardando…</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Modal: Confirmar eliminación --}}
    <flux:modal wire:model="showDeleteModal" title="Eliminar documento" icon="trash">
        <div class="space-y-5 text-sm">
            <div class="rounded-md border p-3 dark:border-neutral-700 bg-red-50 dark:bg-red-900/20">
                <div class="text-xs font-semibold uppercase text-red-600 dark:text-red-300">Advertencia</div>
                <div class="mt-1 text-red-700 dark:text-red-200">
                    Esta acción no se puede deshacer.
                </div>
            </div>

            <p class="text-neutral-700 dark:text-neutral-300">
                ¿Seguro que deseas eliminar
                @if($deletingLabel)
                    <span class="font-semibold">"{{ $deletingLabel }}"</span>?
                @else
                    este documento?
                @endif
            </p>

            {{-- Acciones (dentro del body) --}}
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" @click="$wire.showDeleteModal=false">Cancelar</flux:button>

                <flux:button
                    variant="danger"
                    icon="trash"
                    wire:click="delete"
                    wire:target="delete"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Eliminar</span>
                    <span wire:loading>Eliminando…</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $docs->links() }}
    </div>
</section>
