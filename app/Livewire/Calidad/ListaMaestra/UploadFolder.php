<?php

namespace App\Livewire\Calidad\ListaMaestra;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\LmFolder;
use App\Models\LmFile;

class UploadFolder extends Component
{
    use WithFileUploads;

    /** Modales */
    public bool $open = false;     // Modal: subir carpeta
    public bool $showList = false; // Modal: ver/gestionar archivos

    /** Archivos temporales (subida) */
    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile[] */
    public array $files = [];
    public array $relativePaths = []; // rutas relativas alineadas con $files
    public int $totalBytes = 0;

    /** Límites Laravel/Livewire (ajústalos a tu gusto) */
    protected int $maxTotalBytes = 30 * 1024 * 1024; // 30 MB total

    protected $rules = [
        // 10 MB por archivo y solo PDF
        'files.*'       => 'file|mimes:pdf|max:10240', // 10,240 KB = 10 MB
        'relativePaths' => 'array',
    ];

    /** Computed: ¿ya hay datos cargados? (para cambiar Subir -> Ver/Descargar) */
    public function getHasDataProperty(): bool
    {
        return LmFile::query()->exists();
    }

    /** Computed: carpetas raíz con conteo de archivos directos */
    public function getRootFoldersProperty()
    {
        return LmFolder::whereNull('parent_id')
            ->withCount('files')
            ->orderBy('name')
            ->get();
    }

    /** Computed: archivos desde BD (renombrado para no chocar con $files temporales) */
    public function getAllFilesProperty()
    {
        return LmFile::with('folder')
            ->orderBy('disk_path')
            ->get();
    }

    /** Recalcula el tamaño total y valida límite global */
    public function updatedFiles(): void
    {
        $this->totalBytes = array_sum(array_map(
            fn($f) => $f->getSize(),
            $this->files ?? []
        ));

        if ($this->totalBytes > $this->maxTotalBytes) {
            $this->reset('files', 'relativePaths', 'totalBytes');
            $this->addError(
                'files',
                'El total supera el límite permitido ('.number_format($this->maxTotalBytes/1024/1024, 0).' MB).'
            );
        }
    }

    /** Guarda todos los archivos respetando la estructura de carpetas */
    public function save(): void
    {
        $this->validate();

        foreach ($this->files as $i => $file) {
            // Ruta relativa del archivo (carpeta/subcarpeta/nombre.pdf)
            $rel = $this->relativePaths[$i] ?? $file->getClientOriginalName();
            $rel = ltrim(str_replace('\\', '/', $rel), '/'); // normaliza separadores

            $segments = explode('/', $rel);
            $filename = array_pop($segments);
            $slugPath = implode('/', $segments);
            if ($slugPath === '') {
                $slugPath = 'UPLOAD';
            }

            // Asegura la cadena de carpetas
            $folder = LmFolder::ensurePath($slugPath);

            // Guarda en disco local manteniendo la estructura
            $directory = 'sgc/master/'.$slugPath;
            Storage::disk('local')->makeDirectory($directory);

            $stored = $file->storeAs($directory, $filename, 'local');

            // Registra/actualiza en BD
            LmFile::updateOrCreate(
                ['folder_id' => $folder->id, 'filename' => $filename],
                [
                    'disk_path'  => $stored,
                    'mime'       => $file->getMimeType(),
                    'size_bytes' => $file->getSize(),
                ]
            );
        }

        // Notifica y refresca
        $this->dispatch('folder-uploaded');
        $this->reset(['files','relativePaths','totalBytes','open']);
        $this->dispatch('$refresh');

        session()->flash('ok', 'Carpeta cargada correctamente.');
    }

    /** Elimina un archivo individual (disco + BD) */
    public function deleteFile(int $fileId): void
    {
        $f = LmFile::findOrFail($fileId);
        Storage::disk('local')->delete($f->disk_path);
        $f->delete();

        $this->dispatch('folder-uploaded');
        $this->dispatch('$refresh');
        session()->flash('ok', 'Archivo eliminado.');
    }

    /** Elimina carpeta y todo su contenido (recursivo) */
    public function deleteFolder(int $folderId): void
    {
        $folder = LmFolder::with(['files', 'children'])->findOrFail($folderId);
        $this->deleteFolderRecursive($folder);

        $this->dispatch('folder-uploaded');
        $this->dispatch('$refresh');
        session()->flash('ok', 'Carpeta eliminada.');
    }

    /** Helper recursivo para borrar contenido de una carpeta */
    private function deleteFolderRecursive(LmFolder $folder): void
    {
        foreach ($folder->files as $f) {
            Storage::disk('local')->delete($f->disk_path);
            $f->delete();
        }

        foreach ($folder->children as $child) {
            $child->loadMissing(['files', 'children']);
            $this->deleteFolderRecursive($child);
        }

        $folder->delete();
    }

    public function render()
    {
        return view('livewire.calidad.lista-maestra.upload-folder');
    }
}
