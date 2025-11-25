<div>
    {{-- ACCIONES PRINCIPALES (solo botones) --}}
    @if(!$this->hasData)
        {{-- No hay nada aún → SOLO “Subir ZIP/RAR” --}}
        @can('lista-maestra.upload')
            <flux:button icon="folder-plus" variant="primary" @click="$wire.open = true">
                Subir archivo (ZIP/RAR)
            </flux:button>
        @endcan
    @else
        {{-- Ya hay datos → SOLO “Ver archivo” y “Descargar todo” --}}
        <div class="flex items-center gap-2">
            @can('lista-maestra.files.view')
                <flux:button icon="eye" variant="outline" @click="$wire.showList = true">
                    Ver archivo
                </flux:button>
            @endcan

            @can('lista-maestra.files.download')
                @php
                    $zipUploadedPath = 'sgc/master/zips/lista-maestra.zip';
                    $hasUploadedZip = Storage::disk('local')->exists($zipUploadedPath);

                    $downloadRoute = $hasUploadedZip
                        ? route('lista-maestra.zip-uploaded')
                        : route('lista-maestra.zip-all');
                @endphp

                <flux:button
                    icon="archive-box-arrow-down"
                    as="a"
                    href="{{ $downloadRoute }}"
                    variant="outline">
                    Descargar ZIP
                </flux:button>
            @endcan
        </div>
    @endif

    {{-- MODAL: SUBIR ARCHIVO COMPRIMIDO --}}
    <flux:modal wire:model="open" title="Subir archivo comprimido de Lista Maestra" icon="folder-plus" size="xl">
        <div class="space-y-4">
            <div class="text-sm text-neutral-600">
                Sube un archivo comprimido (.zip o .rar) con todos los documentos de la Lista Maestra.
                Límite: <b>30 MB</b>.
            </div>

            {{-- ANTIGUA SUBIDA POR CARPETA (DESACTIVADA) --}}
            @if(false)
                <div class="border-2 border-dashed rounded-lg p-6 text-center">
                    {{-- aquí iba todo el drag & drop de carpetas --}}
                </div>

                <div class="flex items-center justify-between">
                    {{-- aquí iba el total de MB y botón Subir (carpetas) --}}
                </div>

                @error('files')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            @endif

            {{-- OPCIÓN ÚNICA: Subir un archivo ZIP/RAR --}}
            <div class="border rounded-lg p-4 space-y-3">
                <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                    Subir un archivo ZIP o RAR con todos los documentos
                </div>

                <input
                    type="file"
                    wire:model="zipFile"
                    accept=".zip,.rar"
                    class="w-full rounded-md border px-3 py-2
                        bg-white text-zinc-900 border-zinc-300
                        dark:bg-zinc-900 dark:text-zinc-100 dark:border-zinc-700"
                />

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

    {{-- MODAL: VER / GESTIONAR ARCHIVOS       --}}
    <flux:modal wire:model="showList" title="Lista Maestra — Archivo comprimido" icon="folder" size="5xl">
        <div class="space-y-6">
            {{-- Información del ZIP/RAR de Lista Maestra --}}
            @php
                $disk = \Illuminate\Support\Facades\Storage::disk('local');

                $zipUploadedPath = 'sgc/master/zips/lista-maestra.zip';
                $namePath        = 'sgc/master/zips/lista-maestra.name';

                $hasUploadedZip = $disk->exists($zipUploadedPath);
                $zipSize        = $hasUploadedZip ? $disk->size($zipUploadedPath) : null;

                $zipOriginalName = $hasUploadedZip && $disk->exists($namePath)
                    ? trim($disk->get($namePath))
                    : 'lista-maestra.zip';
            @endphp

            <div class="space-y-2">
                <div class="text-xs font-semibold uppercase text-neutral-500">
                    Archivo comprimido de Lista Maestra
                </div>

                @if ($hasUploadedZip)
                    <div class="flex items-center justify-between rounded-lg border px-3 py-2">
                        <div class="min-w-0">
                            <div class="font-medium truncate">
                                {{ $zipOriginalName }}
                            </div>
                            <div class="text-xs text-neutral-600">
                                Tamaño:
                                <strong>{{ number_format($zipSize / 1024 / 1024, 2) }} MB</strong>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Descargar ZIP/RAR subido --}}
                            <flux:button
                                icon="archive-box-arrow-down"
                                as="a"
                                href="{{ route('lista-maestra.zip-uploaded') }}"
                                size="xs"
                                variant="outline">
                                Descargar archivo
                            </flux:button>

                            {{-- Eliminar ZIP/RAR subido --}}
                            <flux:button
                                icon="trash"
                                size="xs"
                                variant="outline"
                                class="!border-red-500 !text-red-600 hover:!bg-red-50"
                                x-on:click.prevent="if(confirm('¿Eliminar el archivo actual para subir uno nuevo?')) { $wire.deleteZip() }">
                                Eliminar archivo
                            </flux:button>
                        </div>
                    </div>
                @else
                    <div class="rounded-lg border px-3 py-2 text-sm text-neutral-500">
                        No hay ningún archivo comprimido de Lista Maestra subido actualmente.
                    </div>
                @endif
            </div>

            {{-- ANTIGUAS SECCIONES DE CARPETAS Y ARCHIVOS (DESACTIVADAS) --}}
            @if(false)
                {{-- Carpetas raíz --}}
                <div class="space-y-2">
                    ...
                </div>

                {{-- Tabla de archivos --}}
                <div class="space-y-2">
                    ...
                </div>
            @endif
        </div>
    </flux:modal>
</div>
