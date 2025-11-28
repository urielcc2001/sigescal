<?php

namespace App\Livewire\Calidad\ListaMaestra;

use App\Livewire\PageWithDashboard;   // ⬅️ NUEVO: extendemos de PageWithDashboard
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Storage;

class Documentation extends PageWithDashboard   // ⬅️ NOMBRE DE CLASE: Documentation
{
    use WithFileUploads, LivewireAlert;

    /** Modales */
    public bool $open = false;      // Modal: subir archivo
    public bool $showInfo = false;  // Modal: ver info del archivo

    /** Archivo comprimido */
    public $zipFile;

    // Rutas internas en el disco (puedes cambiar el path si quieres)
    protected string $zipDir      = 'sgc/info-doc/zips';           // ⬅️ carpeta donde se guarda
    protected string $zipFilename = 'informacion-documentada.zip'; // ⬅️ nombre físico
    protected string $nameFile    = 'informacion-documentada.name';// ⬅️ archivo con nombre original

    /** Límite (30 MB) */
    protected int $maxTotalBytes = 30 * 1024 * 1024; // 30 MB

    protected $rules = [
        'zipFile' => 'nullable|file|mimes:zip,rar|max:30720', // 30 MB en KB
    ];

    /** Computed: ruta completa del zip */
    public function getZipPathProperty(): string
    {
        return $this->zipDir . '/' . $this->zipFilename;
    }

    /** Computed: ¿ya hay archivo subido? */
    public function getHasDataProperty(): bool
    {
        return Storage::disk('local')->exists($this->zipPath);
    }

    /** Computed: ¿hay ZIP subido? */
    public function getHasUploadedZipProperty(): bool
    {
        return $this->hasData;
    }

    /** Subir ZIP/RAR */
    public function uploadZip(): void
    {
        $this->validateOnly('zipFile');

        if (! $this->zipFile) {
            $this->addError('zipFile', 'Selecciona un archivo ZIP o RAR.');
            return;
        }

        $disk = Storage::disk('local');

        // Asegura carpeta
        $disk->makeDirectory($this->zipDir);

        // Guarda siempre con nombre fijo en el disco
        $path = $this->zipFile->storeAs($this->zipDir, $this->zipFilename, 'local');

        // Guarda el nombre ORIGINAL para usarlo en la descarga
        $originalName = $this->zipFile->getClientOriginalName();
        $disk->put($this->zipDir . '/' . $this->nameFile, $originalName);

        // Limpiar input
        $this->reset('zipFile');

        // Refrescar
        $this->dispatch('$refresh');

        $this->flash('success', 'Archivo comprimido de Información Documentada subido correctamente.');
        $this->redirect(route('calidad.documentacion.index'), true); // ⬅️ NUEVA RUTA
    }

    /** Eliminar ZIP/RAR */
    public function deleteZip(): void
    {
        $disk = Storage::disk('local');

        if ($disk->exists($this->zipPath)) {
            $disk->delete($this->zipPath);
        }

        if ($disk->exists($this->zipDir . '/' . $this->nameFile)) {
            $disk->delete($this->zipDir . '/' . $this->nameFile);
        }

        $this->dispatch('$refresh');
        session()->flash('ok', 'Archivo de Información Documentada eliminado.');
    }

    public function render()
    {
        // ⬅️ VISTA: ahora apunta a lista-maestra/documentation
        return view('livewire.calidad.lista-maestra.documentation');
    }
}
