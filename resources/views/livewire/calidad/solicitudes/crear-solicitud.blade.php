<div class="space-y-6">

    {{-- Encabezado institucional --}}
    <div class="text-center space-y-1">
        <h2 class="text-xs tracking-widest">SUBDIRECCIÓN PLANEACIÓN Y VINCULACIÓN</h2>
        <h2 class="text-xs tracking-widest">COORDINACIÓN DE CALIDAD</h2>

        <h1 class="mt-3 text-lg font-bold uppercase">Solicitud de creación y actualización de documentos</h1>

        {{-- Subtítulo del procedimiento --}}
        <p class="text-sm font-semibold">Procedimiento para el Control de la Información Documentada</p>
    </div>

    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    

    {{-- Bloque: Folio y fecha --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">FOLIO N°</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50"
                       wire:model="folio" readonly>
                @error('folio') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">FECHA</label>
                <input type="date" class="mt-1 w-full rounded-md border p-2"
                       wire:model="fecha">
                @error('fecha') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Bloque: Descripción del documento --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL DOCUMENTO</div>

        {{-- Input por CÓDIGO con datalist: muestra "código — título" en la lista, pero al elegir queda solo el código --}}
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Código del documento</label>
                <input type="text"
                    class="mt-1 w-full rounded-md border p-2"
                    list="lista-codigos"
                    placeholder="Escribe o selecciona..."
                    wire:model.live="codigo">
                <datalist id="lista-codigos">
                    @foreach($documentos as $doc)
                        <option value="{{ $doc->codigo }}" label="{{ $doc->codigo }} — {{ \Illuminate\Support\Str::limit($doc->nombre, 80) }}"></option>
                    @endforeach
                </datalist>
                @error('documento_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Área</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50"
                    value="{{ optional($areas->firstWhere('id', $area_id))->nombre }}" readonly>
                @error('area_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>

        @php
            $docSel = $documentos->firstWhere('id', (int) $documento_id);
        @endphp

        {{-- Ya NO repetimos el campo "Código". Solo mostramos info complementaria --}}
        <div class="grid md:grid-cols-3 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium">Revisión actual</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50"
                    value="{{ $docSel?->revision }}" readonly>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Título</label>
                <input type="text" class="mt-1 w-full rounded-md border p-2 bg-gray-50"
                    value="{{ $docSel?->nombre }}" readonly>
            </div>
        </div>
    </div>


    {{-- Bloque: Tipo de trámite --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="text-sm font-semibold mb-3">TIPO DE TRÁMITE</div>

        <div class="max-w-sm">
            <label class="block text-sm font-medium">Selecciona el tipo</label>
            <select class="mt-1 w-full rounded-md border p-2" wire:model="tipo">
                @foreach($tipos as $t)
                    <option value="{{ $t }}">{{ strtoupper($t) }}</option>
                @endforeach
            </select>
        </div>

        @error('tipo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Bloque: Descripción del cambio --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="text-sm font-semibold mb-3">DESCRIPCIÓN DEL CAMBIO</div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Dice</label>
                <textarea rows="8" class="mt-1 w-full rounded-md border p-2"
                          wire:model.defer="cambio_dice"></textarea>
                @error('cambio_dice') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Debe decir</label>
                <textarea rows="8" class="mt-1 w-full rounded-md border p-2"
                          wire:model.defer="cambio_debe_decir"></textarea>
                @error('cambio_debe_decir') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Bloque: Justificación --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="text-sm font-semibold mb-3">JUSTIFICACIÓN DE LA SOLICITUD</div>
        <textarea rows="4" class="mt-1 w-full rounded-md border p-2"
                  wire:model.defer="justificacion"></textarea>
        @error('justificacion') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Bloque: Requiere capacitación / Difusión --}}
    <div class="border rounded-md p-4 bg-white/60 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <div class="text-sm font-semibold mb-2">REQUIERE CAPACITACIÓN</div>
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" value="1" wire:model="requiere_capacitacion"> <span>SI</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" value="0" wire:model="requiere_capacitacion"> <span>NO</span>
                    </label>
                </div>
            </div>

            <div>
                <div class="text-sm font-semibold mb-2">DIFUSIÓN</div>
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" value="1" wire:model="requiere_difusion"> <span>SI</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" value="0" wire:model="requiere_difusion"> <span>NO</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Botón enviar --}}
{{-- Botón Enviar Solicitud (centrado y visible en ambos modos) --}}
<div class="pt-6 flex justify-center">
    <button
        type="button"
        wire:click="save"
        wire:loading.attr="disabled"
        class="inline-flex items-center gap-2 rounded-md px-6 py-2.5 text-sm font-semibold 
               text-white bg-blue-600 hover:bg-blue-700 focus:outline-none 
               focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-300 
               transition shadow-md active:scale-[0.98]"
        style="background-color:#2563eb;color:#fff;">
        
        {{-- Icono Guardar (SVG) --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="2" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 16v-5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v5m10 0H7m10 0a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2" />
        </svg>

        Enviar solicitud
    </button>

    <span class="ml-3 text-sm text-gray-600 dark:text-gray-400" wire:loading>
        Guardando…
    </span>
</div>


</div>
