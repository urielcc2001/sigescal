<section class="w-full">
    <x-page-heading>
        <x-slot:title>Estado de mis solicitudes</x-slot:title>
        <x-slot:subtitle>Revisa el avance de tus solicitudes</x-slot:subtitle>
    </x-page-heading>

    <div class="mt-4 space-y-4">
        {{-- Filtros --}}
        <div class="flex flex-row items-end gap-3 flex-nowrap">
            {{-- Buscar (ocupa la mayoría) --}}
            <div class="flex-1 min-w-0">
                <label class="block text-sm font-medium">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    class="w-full rounded-md border px-3 py-2"
                    placeholder="Codigo, documento, descripción..."
                />
            </div>

            {{-- Estatus (ancho fijo) --}}
            <div class="shrink-0 w-44">
                <label class="block text-sm font-medium">Estatus</label>
                <select wire:model.live="estado" class="w-full rounded-md border px-3 py-2 bg-zinc-50 text-zinc-900 border-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 dark:bg-zinc-800 dark:text-zinc-100 dark:border-zinc-600 dark:[color-scheme:dark]">
                    <option value="">Todos</option>
                    <option value="en_revision">En revisión</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                </select>
            </div>

            {{-- Por página (ancho fijo) --}}
            <div class="shrink-0 w-28">
                <label class="block text-sm font-medium">Por página</label>
                <select wire:model.live="perPage" class="w-full rounded-md border px-3 py-2 bg-zinc-50 text-zinc-900 border-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 dark:bg-zinc-800 dark:text-zinc-100 dark:border-zinc-600 dark:[color-scheme:dark]">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto rounded-lg border">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-zinc-900/40">
                    <tr>
                        <th class="px-3 py-2 cursor-pointer" wire:click="sortBy('documento_codigo')">Código</th>
                        <th class="px-3 py-2">Título</th>
                        <th class="px-3 py-2 cursor-pointer" wire:click="sortBy('fecha')">Fecha</th>
                        <th class="px-3 py-2">Estatus</th>
                        <th class="px-3 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($rows as $row)
                        <tr class="align-top">
                            {{-- Código (documento_codigo) --}}
                            <td class="px-3 py-2 font-medium">{{ $row->documento?->codigo ?? '—' }}</td>

                            {{-- Título = nombre del documento --}}
                            <td class="px-3 py-2">
                                @if($row->documento)
                                    <div class="leading-tight">
                                        <div class="font-medium">{{ $row->documento->nombre }}</div>
                                        <div class="text-xs text-gray-500">{{ $row->documento->codigo }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>

                            {{-- Fecha --}}
                            <td class="px-3 py-2">
                                {{ \Illuminate\Support\Carbon::parse($row->fecha)->format('Y-m-d') }}
                            </td>

                            {{-- Estatus --}}
                            <td class="px-3 py-2">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                                    'bg-yellow-100 text-yellow-800' => $row->estado === 'en_revision',
                                    'bg-green-100 text-green-800' => $row->estado === 'aprobada',
                                    'bg-red-100 text-red-800' => $row->estado === 'rechazada',
                                ])>{{ str_replace('_',' ', $row->estado) }}</span>
                            </td>

                            {{-- Acciones --}}
                           
                            <td class="px-3 py-2">
                                <div class="flex flex-wrap gap-2">
                                    {{-- Ver (ajusta la ruta si usas otra) --}}
                                    <a href="{{ route('calidad.solicitudes.estado.show', $row->id) }}"
                                    wire:navigate
                                    class="inline-flex items-center rounded-md px-2.5 py-1.5 text-xs font-semibold !text-white !bg-blue-600 hover:!bg-blue-700">
                                    Ver
                                    </a>

                                    {{-- Editar: solo si fue rechazada (ajusta la ruta si la tienes) --}}
                                    @if ($row->estado === 'rechazada')
                                        <a href="{{ route('calidad.solicitudes.estado.edit', $row->id) }}"
                                        wire:navigate
                                        class="inline-flex items-center rounded-md px-2.5 py-1.5 text-xs font-semibold
                                                !text-white !bg-amber-600 hover:!bg-amber-700">
                                            Editar
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-500">
                                Aún no has creado solicitudes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div>{{ $rows->links() }}</div>
    </div>
</section>
