<div class="space-y-6">

    {{-- TÍTULO PRINCIPAL --}}
    <div>
        <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">
            Información documentada
        </h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400">
            Archivo comprimido (ZIP/RAR) con la información documentada vigente del SGC.
        </p>
    </div>

    {{-- TARJETA PRINCIPAL --}}
    <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm
                dark:border-neutral-700 dark:bg-neutral-900">

        {{-- CABECERA DE LA TARJETA --}}
        <div class="flex items-center gap-4 mb-4">
            <div class="rounded-lg bg-indigo-100 p-3 dark:bg-indigo-900/40">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-300"
                     fill="none" stroke="currentColor" stroke-width="1.8"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 7l9-5 9 5-9 5-9-5z"/>
                    <path d="M3 17l9 5 9-5"/>
                    <path d="M3 12l9 5 9-5"/>
                </svg>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">
                    Archivo comprimido del SGC
                </h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Centraliza la descarga de la información documentada en un único archivo ZIP o RAR.
                </p>
            </div>
        </div>

        {{-- CONTENIDO / ACCIONES --}}
        @if (! $this->hasData)
            {{-- NO HAY ARCHIVO SUBIDO --}}
            <div class="rounded-md border border-dashed border-neutral-300 p-4 text-center
                        dark:border-neutral-700">

                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">
                    Aún no se ha cargado el archivo de información documentada.
                </p>

                @if(auth()->user()?->hasRole('Super Admin'))
                    <flux:button icon="folder-plus" variant="primary" @click="$wire.open = true">
                        Subir archivo (ZIP/RAR)
                    </flux:button>
                @else
                    <p class="text-xs text-neutral-500 dark:text-neutral-500 italic">
                        El administrador aún no ha cargado este archivo.
                    </p>
                @endif
            </div>
        @else
            {{-- YA HAY ARCHIVO SUBIDO --}}
            <div class="flex flex-wrap items-center gap-3">

                {{-- Ver info del archivo --}}
                <flux:button icon="eye" variant="outline" @click="$wire.showInfo = true">
                    Ver archivo
                </flux:button>

                {{-- Descargar (cualquier usuario autenticado por ahora) --}}
                <flux:button
                    icon="archive-box-arrow-down"
                    as="a"
                    href="{{ route('calidad.documentacion.download') }}"
                    variant="outline">
                    Descargar información documentada
                </flux:button>

                {{-- Reemplazar: solo Super Admin --}}
                @if(auth()->user()?->hasRole('Super Admin'))
                    <flux:button
                        icon="arrow-up-tray"
                        variant="primary"
                        @click="$wire.open = true">
                        Reemplazar archivo
                    </flux:button>
                @endif
            </div>
        @endif
    </div>

    {{-- MODAL: SUBIR ARCHIVO COMPRIMIDO --}}
    <flux:modal
        wire:model="open"
        title="Subir archivo comprimido de Información Documentada"
        icon="folder-plus"
        size="xl"
    >
        {{-- x-data para manejar estado de drag & drop --}}
        <div class="space-y-4" x-data="{ isDropping: false }">
            <div class="text-sm text-neutral-600">
                Sube un archivo comprimido (.zip o .rar) con toda la Información Documentada.
                Límite: <b>30 MB</b>.
            </div>

            <div class="border rounded-lg p-4 space-y-3">
                <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                    Subir un archivo ZIP o RAR
                </div>

                {{-- INPUT REAL (oculto) --}}
                <input
                    type="file"
                    x-ref="zipInput"
                    wire:model="zipFile"
                    accept=".zip,.rar"
                    class="hidden"
                />

                {{-- ÁREA DE DRAG & DROP / CLICK --}}
                <div
                    class="flex flex-col items-center justify-center gap-2 rounded-md border-2 border-dashed border-neutral-300 px-4 py-8 text-center cursor-pointer
                        dark:border-neutral-700
                        transition-colors"
                    :class="{
                        'border-indigo-500 bg-indigo-50/70 dark:bg-indigo-900/30': isDropping
                    }"
                    x-on:click="$refs.zipInput.click()"
                    x-on:dragover.prevent="isDropping = true"
                    x-on:dragleave.prevent="isDropping = false"
                    x-on:drop.prevent="
                        isDropping = false;
                        const dt = $event.dataTransfer;
                        if (dt && dt.files && dt.files.length) {
                            $refs.zipInput.files = dt.files;
                            $refs.zipInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    "
                >
                    <svg class="w-10 h-10 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor"
                        stroke-width="1.6" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 15a4 4 0 014-4h1"></path>
                        <path d="M12 4v9"></path>
                        <path d="M9 7l3-3 3 3"></path>
                        <path d="M17 9a4 4 0 010 8H7a4 4 0 01-4-4"></path>
                    </svg>

                    <p class="text-sm text-neutral-700 dark:text-neutral-200">
                        Arrastra y suelta el archivo aquí
                    </p>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                        o haz clic para seleccionarlo desde tu equipo
                    </p>
                    <p class="text-[11px] text-neutral-400 dark:text-neutral-500">
                        Formatos permitidos: <span class="font-medium">.zip, .rar</span> — hasta 30 MB
                    </p>
                </div>

                @error('zipFile')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if ($zipFile)
                    <p class="text-xs text-neutral-500">
                        Archivo seleccionado:
                        <strong>{{ $zipFile->getClientOriginalName() }}</strong>
                        @php
                            $sizeMb = $zipFile->getSize() ? $zipFile->getSize() / 1024 / 1024 : null;
                        @endphp
                        @if($sizeMb)
                            — Tamaño aprox:
                            <strong>{{ number_format($sizeMb, 2) }} MB</strong>
                        @endif
                    </p>
                @endif

                <div class="flex justify-end gap-2">
                    <flux:button variant="ghost" @click="$wire.open = false">
                        Cancelar
                    </flux:button>

                    <flux:button
                        variant="outline"
                        icon="archive-box-arrow-down"
                        wire:click="uploadZip"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Subir archivo</span>
                        <span wire:loading>Subiendo…</span>
                    </flux:button>
                </div>
            </div>
        </div>
    </flux:modal>


    {{-- MODAL: INFO DEL ARCHIVO --}}
    <flux:modal
        wire:model="showInfo"
        title="Información Documentada — Archivo comprimido"
        icon="folder"
        size="lg"
    >
        <div class="space-y-4">
            @php
                $disk = \Illuminate\Support\Facades\Storage::disk('local');

                $zipPath  = $this->zipPath;
                $namePath = $this->zipDir . '/' . $this->nameFile;

                $hasZip   = $disk->exists($zipPath);
                $zipSize  = $hasZip ? $disk->size($zipPath) : null;

                $zipName = $hasZip && $disk->exists($namePath)
                    ? trim($disk->get($namePath))
                    : 'informacion-documentada.zip';
            @endphp

            <div class="space-y-2">
                <div class="text-xs font-semibold uppercase text-neutral-500">
                    Archivo comprimido de Información Documentada
                </div>

                @if ($hasZip)
                    <div class="flex items-center justify-between rounded-lg border px-3 py-2">
                        <div class="min-w-0">
                            <div class="font-medium truncate">
                                {{ $zipName }}
                            </div>
                            <div class="text-xs text-neutral-600">
                                Tamaño:
                                <strong>{{ number_format($zipSize / 1024 / 1024, 2) }} MB</strong>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Descargar --}}
                            <flux:button
                                icon="archive-box-arrow-down"
                                as="a"
                                href="{{ route('calidad.documentacion.download') }}"
                                size="xs"
                                variant="outline">
                                Descargar archivo
                            </flux:button>

                            {{-- Eliminar: solo Super Admin por ahora --}}
                            @if(auth()->user()?->hasRole('Super Admin'))
                                <flux:button
                                    icon="trash"
                                    size="xs"
                                    variant="outline"
                                    class="!border-red-500 !text-red-600 hover:!bg-red-50"
                                    x-on:click.prevent="if(confirm('¿Eliminar el archivo actual para subir uno nuevo?')) { $wire.deleteZip() }">
                                    Eliminar archivo
                                </flux:button>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="rounded-lg border px-3 py-2 text-sm text-neutral-500">
                        No hay ningún archivo comprimido de Información Documentada subido actualmente.
                    </div>
                @endif
            </div>
        </div>
    </flux:modal>
</div>
