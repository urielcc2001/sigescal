<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            Revisar solicitudes
        </x-slot:title>
        <x-slot:subtitle>
            Solicitudes en revisión, listas para evaluar.
        </x-slot:subtitle>
        <x-slot:buttons>
            {{-- Botones extra si necesitas --}}
        </x-slot:buttons>
    </x-page-heading>

    <div class="mt-4 space-y-4">
        {{-- Filtros --}}
        <div class="flex flex-col gap-3 md:flex-row md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.400ms="search"
                       class="w-full rounded-md border px-3 py-2"
                       placeholder="Folio, documento, solicitante, justificación..." />
            </div>

            <div>
                <label class="block text-sm font-medium">Por página</label>
                <select wire:model.live="perPage" class="rounded-md border px-3 py-2">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto rounded-lg border">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-3 py-2 cursor-pointer" wire:click="sortBy('folio')">Código</th>
                        <th class="px-3 py-2">Nombre</th>
                        <th class="px-3 py-2 cursor-pointer" wire:click="sortBy('fecha')">Fecha</th>
                        <th class="px-3 py-2">Solicitante</th>
                        <th class="px-3 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($rows as $row)
                        <tr class="align-top">
                            {{-- Código (folio) --}}
                            <td class="px-3 py-2 font-medium">
                                {{ $row->folio }}
                            </td>

                            {{-- Nombre del documento (o clave + nombre si aplica) --}}
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

                            {{-- Solicitante --}}
                            <td class="px-3 py-2">
                                {{ optional($row->usuario)->name ?? '—' }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-3 py-2">
                                <a href="{{ route('calidad.solicitudes.revisar.show', $row->id) }}"
                                wire:navigate
                                class="inline-flex items-center rounded-md px-2.5 py-1.5 text-xs font-semibold !text-white !bg-blue-600 hover:!bg-blue-700">
                                Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-500">
                                No hay solicitudes en revisión.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div>
            {{ $rows->links() }}
        </div>
    </div>
</section>
