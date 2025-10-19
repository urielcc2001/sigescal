<div class="p-4 lg:p-6 space-y-4">
    {{-- Encabezado + filtros --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 space-y-3">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h1 class="text-lg font-semibold">Organización y Personal</h1>
                <p class="text-sm text-zinc-500">Consulta y reasigna titulares del organigrama.</p>
            </div>

            <div class="flex items-end gap-2 flex-wrap">
                <div class="min-w-56">
                    <flux:label>Bloque</flux:label>
                    <select
                        wire:model.live="bloque"
                        class="w-full rounded border-zinc-300 dark:bg-zinc-800 dark:border-zinc-700">
                        <option value="">Todos</option>
                        <option value="vinculacion">Planeación y Vinculación</option>
                        <option value="servicios">Servicios Administrativos</option>
                        <option value="academico">Académico</option>
                    </select>
                </div>

                <div class="min-w-48">
                    <flux:label>Nivel</flux:label>
                    <select
                        wire:model.live="nivel"
                        class="w-full rounded border-zinc-300 dark:bg-zinc-800 dark:border-zinc-700">
                        <option value="">Todos</option>
                        <option value="director">Dirección</option>
                        <option value="subdirector">Subdirecciones</option>
                        <option value="calidad">Calidad</option>
                        <option value="jefe_depto">Jefaturas</option>
                    </select>
                </div>

                <div class="min-w-64">
                    <flux:label>Departamento</flux:label>
                    <select
                        wire:model.live="departmentId"
                        class="w-full rounded border-zinc-300 dark:bg-zinc-800 dark:border-zinc-700">
                        <option value="">Todos</option>
                        @foreach($departments as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Búsqueda --}}
                <div class="min-w-72">
                    <flux:label>Buscar</flux:label>
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Puesto, departamento o titular"/>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-600 dark:text-zinc-300">
                    <tr>
                        <th class="px-4 py-2 text-left">Puesto</th>
                        <th class="px-4 py-2 text-left">Área / Departamento</th>
                        <th class="px-4 py-2 text-left">Titular vigente</th>
                        <th class="px-4 py-2 text-left">Desde</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($positions as $pos)
                        @php
                            $titular = $pos->vigente;
                            $depName = $pos->department?->nombre ?? '—';
                        @endphp
                        <tr class="hover:bg-zinc-50/60 dark:hover:bg-zinc-800/40">
                            <td class="px-4 py-2">
                                <div class="font-medium">{{ $pos->titulo }}</div>
                                <div class="text-xs text-zinc-500">{{ ucfirst(str_replace('_',' ', $pos->nivel)) }}</div>
                            </td>
                            <td class="px-4 py-2">{{ $depName }}</td>
                            <td class="px-4 py-2">
                                @if($titular?->nombre)
                                    <div class="font-medium">{{ $titular->nombre }}</div>
                                    @if($titular->correo)
                                        <div class="text-xs text-zinc-500">{{ $titular->correo }}</div>
                                    @endif
                                    @if($titular->telefono)
                                        <div class="text-xs text-zinc-500">{{ $titular->telefono }}</div>
                                    @endif
                                @else
                                    <span class="text-xs px-2 py-1 rounded bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                        Vacante / No asignado
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($titular?->inicio)
                                    {{ \Illuminate\Support\Carbon::parse($titular->inicio)->translatedFormat('d/M/Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                @can('org.personal.edit')
                                    <flux:button size="sm" variant="outline" icon="pencil" wire:click="openAssign({{ $pos->id }})">
                                        Editar
                                    </flux:button>
                                @else
                                    <flux:button size="sm" variant="outline" icon="lock-closed" disabled title="Sin permiso para editar">
                                        Editar
                                    </flux:button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-zinc-500">
                                No se encontraron resultados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $positions->links() }}
        </div>
    </div>

    {{-- Modal de reasignación (Flux) --}}
    <flux:modal wire:model="showAssignModal">
        <div class="space-y-3">
            <flux:heading size="lg">
                Gestionar titular
                @if($positionId)
                    <span class="text-sm font-normal text-zinc-500">
                        — {{ \App\Models\OrgPosition::find($positionId)?->titulo }}
                    </span>
                @endif
            </flux:heading>

            <div class="space-y-2">
                <flux:label class="font-medium">¿Qué deseas hacer?</flux:label>

                <div class="flex flex-col gap-2">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" class="rounded border-zinc-300"
                            value="editar" wire:model="modo">
                        <span class="text-sm">Editar datos del titular vigente
                            <span class="text-xs text-zinc-500"></span>
                        </span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="radio" class="rounded border-zinc-300"
                            value="nuevo" wire:model="modo">
                        <span class="text-sm">Asignar nuevo titular
                            <span class="text-xs text-zinc-500"></span>
                        </span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="radio" class="rounded border-zinc-300"
                            value="vacante" wire:model="modo">
                        <span class="text-sm">Dejar vacante
                            <span class="text-xs text-zinc-500"></span>
                        </span>
                    </label>

                    @error('modo')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if($modo !== 'vacante')
                <div class="grid gap-3">
                    <div>
                        <flux:label>Nombre completo</flux:label>
                        <flux:input wire:model="nombre" placeholder="Ej. JUAN PÉREZ"/>
                        @error('nombre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <flux:label>Correo (opcional)</flux:label>
                            <flux:input type="email" wire:model="correo" placeholder="correo@dominio.com"/>
                            @error('correo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <flux:label>Teléfono (opcional)</flux:label>
                            <flux:input wire:model="telefono" placeholder="xxx xxx xxxx"/>
                            @error('telefono') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <flux:label>Fecha de inicio del titular</flux:label>
                            <input
                                type="date"
                                wire:model="inicio"
                                class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-zinc-400
                                    dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-100"
                                min="1900-01-01"
                                max="2100-12-31"
                            />
                            @error('inicio') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex justify-end gap-2 mt-2">
                <flux:button variant="outline" @click="$wire.showAssignModal = false">
                    Cancelar
                </flux:button>
                @can('org.personal.edit')
                    <flux:button variant="primary"
                                wire:click="saveAssignment"
                                wire:loading.attr="disabled"
                                @disabled="$wire.modo === ''">
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading>Guardando…</span>
                    </flux:button>
                @else
                    <flux:button variant="outline" disabled title="Sin permiso">Guardar</flux:button>
                @endcan
            </div>
        </div>
    </flux:modal>
</div>
