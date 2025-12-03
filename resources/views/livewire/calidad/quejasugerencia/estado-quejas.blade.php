<div class="space-y-6">

    <h2 class="text-xl font-semibold text-center">
        Consulta el estado de tu queja o sugerencia
    </h2>

    {{-- BUSCADOR DE FOLIO --}}
    <div class="mx-auto max-w-md rounded-lg border bg-white/70 p-4 dark:bg-neutral-900/70 dark:border-neutral-700 space-y-3">
        <flux:label>Folio</flux:label>

        <flux:input 
            wire:model.defer="folio" 
            placeholder="Ej. Q-20251128-0001"
        />

        @error('folio') 
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
        @enderror

        <div class="flex justify-end mt-2">
            <flux:button 
                variant="primary" 
                icon="magnifying-glass"
                wire:click="buscar"
            >
                Buscar
            </flux:button>
        </div>
    </div>

    {{-- RESULTADO --}}
    @if($found)
        <div class="mx-auto max-w-2xl rounded-lg border bg-white/70 p-5 dark:bg-neutral-900/70 dark:border-neutral-700">
            
            <div class="grid gap-3 sm:grid-cols-2 text-sm">
                <div>
                    <div class="text-neutral-500">Folio</div>
                    <div class="font-medium font-mono">{{ $found->folio }}</div>
                </div>

                <div>
                    <div class="text-neutral-500">Fecha</div>
                    <div class="font-medium">
                        {{ optional($found->created_at)->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div>
                    <div class="text-neutral-500">Tipo</div>
                    <div class="font-medium capitalize">{{ $found->tipo }}</div>
                </div>

                <div>
                    <div class="text-neutral-500">Estado</div>
                    @php
                        $color = match($found->estado) {
                            'abierta'      => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
                            'en_proceso'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                            'respondida'   => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200',
                            'cerrada'      => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
                            default        => 'bg-neutral-100 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200',
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs {{ $color }}">
                        {{ str_replace('_',' ', $found->estado) }}
                    </span>
                </div>
            </div>

            <div class="mt-4">
                <div class="text-neutral-500 mb-1">Descripción</div>
                <div class="rounded-md border p-3 dark:border-neutral-700 whitespace-pre-line">
                    {{ $found->descripcion }}
                </div>
            </div>

            <div class="mt-4">
                <div class="text-neutral-500 mb-1">Respuesta</div>
                @if($found->respuesta)
                    <div class="rounded-md border p-3 dark:border-neutral-700 whitespace-pre-line">
                        {{ $found->respuesta }}
                    </div>
                    @if($found->respondida_at)
                        <div class="text-xs mt-1 text-neutral-500">
                            Respondida el {{ $found->respondida_at->format('d/m/Y H:i') }}
                        </div>
                    @endif
                @else
                    <div class="text-neutral-500">Aún no hay respuesta.</div>
                @endif
            </div>

            {{-- BOTÓN DESCARGAR FORMATO --}}
            <div class="mt-6 flex justify-end">
                @if($found->respuesta && in_array($found->estado, ['respondida', 'cerrada']))
                    <flux:button
                        tag="a"
                        icon="arrow-down-tray"
                        variant="outline"
                        href="{{ route('quejas.publico.pdf', $found) }}"
                        target="_blank"
                    >
                        Descargar formato PDF
                    </flux:button>
                @else
                    <p class="text-xs text-neutral-500">
                        El formato PDF estará disponible cuando tu queja haya sido respondida.
                    </p>
                @endif
            </div>
        </div>
    @endif
</div>
