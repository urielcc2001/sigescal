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
            <flux:button
                tag="a"
                href="{{ route('calidad.lista-maestra.pdf', ['areaId' => $areaId, 'search' => $search]) }}"
                target="_blank" rel="noopener"
                icon="check"
                variant="primary"
                >
                Descargar PDF
            </flux:button>
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
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
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
                        <td class="px-4 py-2 text-sm text-zinc-400 dark:text-zinc-500">—</td>
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

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $docs->links() }}
    </div>
</section>
