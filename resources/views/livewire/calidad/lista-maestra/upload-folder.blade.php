<div>
    {{-- ACCIONES PRINCIPALES (solo botones) --}}
    @if(!$this->hasData)
        {{-- No hay nada aún → SOLO “Subir carpeta” --}}
        @can('lista-maestra.upload')
            <flux:button icon="folder-plus" variant="primary" @click="$wire.open = true">
                Subir carpeta
            </flux:button>
        @endcan
    @else
        {{-- Ya hay datos → SOLO “Ver archivos” y “Descargar todo” --}}
        <div class="flex items-center gap-2">
            @can('lista-maestra.files.view')
                <flux:button icon="eye" variant="outline" @click="$wire.showList = true">
                    Ver archivos
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

            @can('lista-maestra.files.download')
                <flux:button
                    icon="archive-box-arrow-down"
                    as="a"
                    href="{{ $downloadRoute }}"
                    variant="outline">
                    Descargar todo (ZIP)
                </flux:button>
            @endcan

            @endcan
        </div>
    @endif

    {{-- MODAL: SUBIR CARPETA          --}}
    <flux:modal wire:model="open" title="Subir carpeta a Lista Maestra" icon="folder-plus" size="xl">
        <div class="space-y-4" x-data="{
            pickFolder(e) {
                const files = Array.from(e.target.files || []);
                const rels  = files.map(f => f.webkitRelativePath || f.name);
                $wire.uploadMultiple('files', files, () => { $wire.relativePaths = rels; });
            }
        }">
            <div class="text-sm text-neutral-600">
                Arrastra una carpeta completa o elígela. Límite total: <b>150 MB</b>. Solo PDF. Límite por archivo: <b>50 MB</b>.
            </div>

            <div class="border-2 border-dashed rounded-lg p-6 text-center">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <flux:icon name="folder-open" class="h-5 w-5"/>
                    <span>Elegir carpeta…</span>
                    <input type="file" class="hidden" x-on:change="pickFolder" webkitdirectory directory multiple />
                </label>

                <div class="mt-3 text-xs text-neutral-500" wire:loading wire:target="files">
                    Cargando archivos…
                </div>

                {{-- PREVISUALIZACIÓN DE ARCHIVOS TEMPORALES (usar métodos, NO ->filename) --}}
                @if($files)
                    <div class="mt-4 max-h-52 overflow-auto text-left text-sm">
                        @foreach($files as $tf)
                            <div class="truncate">
                                {{ $relativePaths[$loop->index] ?? $tf->getClientOriginalName() }}
                                <span class="text-neutral-500">
                                    ({{ number_format($tf->getSize() / 1024, 0) }} KB)
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm">
                    Total: <b>{{ number_format($totalBytes/1024/1024, 2) }} MB</b>
                </div>
                <div class="flex gap-2">
                    <flux:button variant="ghost" @click="$wire.open = false">Cancelar</flux:button>
                    <flux:button variant="primary" icon="arrow-up-tray" wire:click="save" wire:loading.attr="disabled">
                        <span wire:loading.remove>Subir</span>
                        <span wire:loading>Subiendo…</span>
                    </flux:button>
                </div>
            </div>

            {{-- OPCIÓN 2: Subir un solo ZIP --}}
            <div class="border rounded-lg p-4 space-y-3">
                <div class="text-sm font-medium text-neutral-800 dark:text-neutral-100">
                    O subir un archivo ZIP con todos los documentos
                </div>

                <input
                    type="file"
                    wire:model="zipFile"
                    accept=".zip"
                    class="w-full rounded-md border px-3 py-2
                           bg-white text-zinc-900 border-zinc-300
                           dark:bg-zinc-900 dark:text-zinc-100 dark:border-zinc-700"
                >

                @error('zipFile')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if ($zipFile)
                    <p class="text-xs text-neutral-500">
                        ZIP seleccionado:
                        <strong>{{ $zipFile->getClientOriginalName() }}</strong>
                    </p>
                @endif

                <div class="flex justify-end">
                    <flux:button
                        variant="outline"
                        icon="archive-box-arrow-down"
                        wire:click="uploadZip"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Subir ZIP</span>
                        <span wire:loading>Subiendo…</span>
                    </flux:button>
                </div>
            </div>

            @error('files') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>
    </flux:modal>

    {{-- MODAL: VER / GESTIONAR ARCHIVOS       --}}
    <flux:modal wire:model="showList" title="Lista Maestra — Archivos y Carpetas" icon="folder" size="5xl">
        <div class="space-y-6">
            {{-- Información del ZIP de Lista Maestra --}}
            @php
                $zipUploadedPath = 'sgc/master/zips/lista-maestra.zip';
                $hasUploadedZip = \Illuminate\Support\Facades\Storage::disk('local')->exists($zipUploadedPath);
                $zipSize = $hasUploadedZip
                    ? \Illuminate\Support\Facades\Storage::disk('local')->size($zipUploadedPath)
                    : null;
            @endphp

            <div class="space-y-2">
                <div class="text-xs font-semibold uppercase text-neutral-500">
                    ZIP de Lista Maestra
                </div>

                @if ($hasUploadedZip)
                    <div class="flex items-center justify-between rounded-lg border px-3 py-2">
                        <div class="min-w-0">
                            <div class="font-medium truncate">
                                lista-maestra.zip
                            </div>
                            <div class="text-xs text-neutral-600">
                                Tamaño:
                                <strong>{{ number_format($zipSize / 1024 / 1024, 2) }} MB</strong>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Descargar ZIP subido --}}
                            <flux:button
                                icon="archive-box-arrow-down"
                                as="a"
                                href="{{ route('lista-maestra.zip-uploaded') }}"
                                size="xs"
                                variant="outline">
                                Descargar ZIP
                            </flux:button>

                            {{-- Eliminar ZIP subido --}}
                            <flux:button
                                icon="trash"
                                size="xs"
                                variant="outline"
                                class="!border-red-500 !text-red-600 hover:!bg-red-50"
                                x-on:click.prevent="if(confirm('¿Eliminar el ZIP actual para subir uno nuevo?')) { $wire.deleteZip() }">
                                Eliminar ZIP
                            </flux:button>
                        </div>
                    </div>
                @else
                    <div class="rounded-lg border px-3 py-2 text-sm text-neutral-500">
                        No hay ningún ZIP de Lista Maestra subido actualmente.
                    </div>
                @endif
            </div>


            {{-- Carpetas raíz (nota: files_count solo cuenta archivos directos, no descendientes) --}}
            <div class="space-y-2">
                <div class="text-xs font-semibold uppercase text-neutral-500">Carpetas (nivel raíz)</div>

                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($this->rootFolders as $root)
                        <div class="rounded-lg border p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="truncate font-medium">{{ $root->name }}</div>
                                    <div class="text-xs text-neutral-500 truncate">
                                        {{ $root->slug_path }}
                                    </div>
                                </div>
                                <div class="shrink-0 text-xs text-neutral-600">
                                    {{ $root->files_count }} archivos
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <flux:button
                                    icon="archive-box-arrow-down"
                                    as="a"
                                    href="{{ route('lista-maestra.folder.zip', ['path' => $root->slug_path]) }}"
                                    size="xs"
                                    variant="outline">
                                    Descargar carpeta (ZIP)
                                </flux:button>

                                {{-- Eliminar carpeta completa (recursivo) --}}
                                <flux:button
                                    icon="trash"
                                    size="xs"
                                    variant="outline"
                                    class="!border-red-500 !text-red-600 hover:!bg-red-50"
                                    x-on:click.prevent="if(confirm('¿Eliminar esta carpeta y todo su contenido?')) { $wire.deleteFolder({{ $root->id }}) }">
                                    Eliminar carpeta
                                </flux:button>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-neutral-500">Sin carpetas raíz registradas.</div>
                    @endforelse
                </div>
            </div>

            {{-- Archivos (colección desde BD: $this->allFiles) --}}
            <div class="space-y-2">
                <div class="text-xs font-semibold uppercase text-neutral-500">Archivos</div>

                <div class="overflow-x-auto rounded-lg border">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-50 dark:bg-neutral-900/40">
                            <tr class="text-left">
                                <th class="px-3 py-2">Archivo</th>
                                <th class="px-3 py-2">Carpeta</th>
                                <th class="px-3 py-2">Tamaño</th>
                                <th class="px-3 py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($this->allFiles as $dbf)
                                <tr>
                                    <td class="px-3 py-2 truncate">{{ $dbf->filename }}</td>
                                    <td class="px-3 py-2 text-neutral-600 truncate">{{ $dbf->folder?->slug_path }}</td>
                                    <td class="px-3 py-2 text-neutral-600">{{ number_format($dbf->size_bytes/1024, 0) }} KB</td>
                                    <td class="px-3 py-2">
                                        <div class="flex gap-2">
                                            <flux:button
                                                icon="trash"
                                                size="xs"
                                                variant="outline"
                                                class="!border-red-500 !text-red-600 hover:!bg-red-50"
                                                x-on:click.prevent="if(confirm('¿Eliminar este archivo?')) { $wire.deleteFile({{ $dbf->id }}) }">
                                                Eliminar
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-3 py-6 text-center text-neutral-500">Sin archivos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </flux:modal>
</div>
