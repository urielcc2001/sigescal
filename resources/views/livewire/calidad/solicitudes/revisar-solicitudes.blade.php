<section class="w-full">
    <x-page-heading>
        <x-slot:title>Revisar solicitudes</x-slot:title>
        <x-slot:subtitle>Solicitudes en revisión y revisadas.</x-slot:subtitle>
        <x-slot:buttons></x-slot:buttons>
    </x-page-heading>

    <div class="mt-4 space-y-4">

        {{-- Filtros (1 sola línea) --}}
        <div class="flex flex-row items-end gap-3 flex-nowrap">
            {{-- Buscar (ocupa la mayoría) --}}
            <div class="flex-1 min-w-0">
                <label class="block text-sm font-medium">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.400ms="search"
                       class="w-full rounded-md border px-3 py-2"
                       placeholder="Codigo, documento, solicitante, justificación..." />
            </div>

            {{-- Vista --}}
            <div class="shrink-0 w-40">
                <label class="block text-sm font-medium">Vista</label>
                <select wire:model.live="vista" class="w-full rounded-md border px-3 py-2 bg-zinc-50 text-zinc-900 border-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 dark:bg-zinc-800 dark:text-zinc-100 dark:border-zinc-600 dark:[color-scheme:dark]">
                    <option value="por_revisar">Por revisar</option>
                    <option value="revisadas">Revisadas</option>
                </select>
            </div>

            {{-- Desde --}}
            <div class="shrink-0 w-40">
                <label class="block text-sm font-medium">Desde</label>
                <input type="date" wire:model.live="fecha_inicio"
                       class="w-full rounded-md border px-3 py-2" />
            </div>

            {{-- Hasta --}}
            <div class="shrink-0 w-40">
                <label class="block text-sm font-medium">Hasta</label>
                <input type="date" wire:model.live="fecha_fin"
                       class="w-full rounded-md border px-3 py-2" />
            </div>

            {{-- Por página --}}
            <div class="shrink-0 w-28">
                <label class="block text-sm font-medium">Por página</label>
                <select wire:model.live="perPage" class="w-full rounded-md border px-3 py-2 bg-zinc-50 text-zinc-900 border-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 dark:bg-zinc-800 dark:text-zinc-100 dark:border-zinc-600 dark:[color-scheme:dark]">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
            </div>
        </div>

        {{-- (Opcional) Botón limpiar fechas --}}
        @if($fecha_inicio || $fecha_fin)
            <div class="text-right">
                <button wire:click="clearDates"
                        class="text-xs underline text-blue-600 hover:text-blue-700">
                    Limpiar fechas
                </button>
            </div>
        @endif

        {{-- Tabla --}}
        <div class="overflow-x-auto rounded-lg border">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-zinc-900/40">
                    <tr>
                        <th class="px-3 py-2 cursor-pointer" wire:click="sortBy('documento_codigo')">Código</th>
                        <th class="px-3 py-2">Nombre</th>
                        <th class="px-3 py-2 cursor-pointer" wire:click="sortBy('fecha')">
                            Fecha
                            @if($sortField === 'fecha')
                                <span class="font-mono text-[10px]">
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                </span>
                            @endif
                        </th>
                        <th class="px-3 py-2">Solicitante</th>

                        @if($vista === 'revisadas')
                            <th class="px-3 py-2">Estado</th>
                        @endif

                        <th class="px-3 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($rows as $row)
                        <tr class="align-top">
                            <td class="px-3 py-2 font-medium">
                                {{ $row->documento?->codigo ?? $row->codigo_nuevo ?? '—' }}
                                @if($row->tipo === 'creacion')
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 text-[10px] font-semibold mr-1">
                                        CREACIÓN
                                    </span>
                                @endif
                            </td>

                            <td class="px-3 py-2">
                                @if($row->documento)
                                    <div class="leading-tight">
                                        <div class="font-medium">{{ $row->documento->nombre }}</div>
                                        <div class="text-xs text-gray-500">{{ $row->documento->codigo }}</div>
                                    </div>
                                @elseif($row->tipo === 'creacion')
                                    <div class="leading-tight">
                                        <div class="font-medium">{{ $row->titulo_nuevo ?? 'Documento nuevo' }}</div>
                                        <div class="text-xs text-gray-500">{{ $row->codigo_nuevo ?? '—' }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>

                            <td class="px-3 py-2">
                                {{ \Illuminate\Support\Carbon::parse($row->fecha)->format('Y-m-d') }}
                            </td>

                            <td class="px-3 py-2">
                                {{ optional($row->usuario)->name ?? '—' }}
                            </td>

                            @if($vista === 'revisadas')
                                <td class="px-3 py-2">
                                    @if($row->estado === 'aprobada')
                                        <span class="text-green-600 font-medium">Aprobada</span>
                                    @else
                                        <span class="text-red-600 font-medium">Rechazada</span>
                                    @endif
                                </td>
                            @endif

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
                            <td colspan="{{ $vista === 'revisadas' ? 6 : 5 }}" class="px-3 py-6 text-center text-sm text-gray-500">
                                No hay solicitudes que coincidan con los filtros.
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
