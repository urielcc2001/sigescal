<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Area;
use App\Models\ListaMaestra;
use App\Models\Solicitud;
use App\Models\SolicitudAdjunto;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class CrearSolicitud extends PageWithDashboard
{
    use WithFileUploads;

    public string $codigo = '';

    public ?int $documento_id = null;
    public ?int $area_id = null;
    public string $folio = '';
    public string $fecha = '';
    public string $tipo = 'modificacion'; // creacion | modificacion | baja
    public string $cambio_dice = '';
    public string $cambio_debe_decir = '';
    public string $justificacion = '';
    public bool $requiere_capacitacion = false;
    public bool $requiere_difusion = true;

    /** NUEVO: arrays de archivos por sección */
    public array $imagenesDice = [];
    public array $imagenesDebeDecir = [];

    // Catálogos
    public array $tipos = ['creacion', 'modificacion', 'baja'];
    public $documentos; // colección para el select
    public $areas;      // colección para mostrar/uso interno

    public function mount(): void
    {
        $this->fecha = now()->toDateString();
        $this->folio = $this->generarFolio();

        // Cargar catálogos
        $this->documentos = ListaMaestra::orderBy('codigo')->get(['id','codigo','nombre','area_id','revision']);
        $this->areas = Area::orderBy('nombre')->get(['id','nombre']);
    }

    /** Autocompletar área al elegir código */
    public function updatedCodigo($value): void
    {
        $doc = $this->documentos->firstWhere('codigo', $value);

        if ($doc) {
            $this->documento_id = $doc->id;
            $this->area_id      = $doc->area_id;
        } else {
            $this->documento_id = null;
            $this->area_id      = null;
        }
    }

    protected function rules(): array
    {
        return [
            'folio'                 => ['required','string','max:50'],
            'fecha'                 => ['required','date'],
            'documento_id'          => ['nullable','exists:lista_maestra,id'],
            'area_id'               => ['nullable','exists:areas,id'],
            'tipo'                  => ['required','in:creacion,modificacion,baja'],
            'cambio_dice'           => ['nullable','string'],
            'cambio_debe_decir'     => ['nullable','string'],
            'justificacion'         => ['required','string','min:5'],
            'requiere_capacitacion' => ['boolean'],
            'requiere_difusion'     => ['boolean'],

            // archivos
            'imagenesDice.*'        => ['image','max:2048'],       // 2 MB por imagen (ajusta)
            'imagenesDebeDecir.*'   => ['image','max:2048'],
        ];
    }

    /** Quitar previews antes de guardar */
    public function removeDice(int $index): void
    {
        unset($this->imagenesDice[$index]);
        $this->imagenesDice = array_values($this->imagenesDice);
    }
    public function removeDebeDecir(int $index): void
    {
        unset($this->imagenesDebeDecir[$index]);
        $this->imagenesDebeDecir = array_values($this->imagenesDebeDecir);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // 1) Crear la solicitud
            $solicitud = Solicitud::create([
                'folio'                 => $this->folio,
                'fecha'                 => $this->fecha,
                'documento_id'          => $this->documento_id,
                'area_id'               => $this->area_id ?? (Auth::user()->area_id ?? null),
                'user_id'               => Auth::id(),
                'tipo'                  => $this->tipo,
                'cambio_dice'           => $this->cambio_dice,
                'cambio_debe_decir'     => $this->cambio_debe_decir,
                'justificacion'         => $this->justificacion,
                'requiere_capacitacion' => $this->requiere_capacitacion,
                'requiere_difusion'     => $this->requiere_difusion,
                'estado'                => 'en_revision',
            ]);

            // 2) Guardar adjuntos de cada sección
            $this->guardarAdjuntos($solicitud->id, 'cambio_dice', $this->imagenesDice);
            $this->guardarAdjuntos($solicitud->id, 'cambio_debe_decir', $this->imagenesDebeDecir);
        });

        // 3) Reset para nueva captura
        $this->resetExcept(['documentos','areas','tipos']);
        $this->fecha = now()->toDateString();
        $this->folio = $this->generarFolio();
        $this->requiere_difusion = true;

        session()->flash('success', 'Solicitud enviada correctamente.');
        return redirect()->route('calidad.solicitudes.estado');
    }

    private function guardarAdjuntos(int $solicitudId, string $seccion, array $files): void
    {
        foreach ($files as $i => $file) {
            // guardar archivo en /storage/app/public/solicitudes/{id}/{seccion}
            $path = $file->storePublicly("solicitudes/{$solicitudId}/{$seccion}", 'public');

            // metadatos
            $original = $file->getClientOriginalName();
            $mime     = $file->getMimeType();
            $size     = $file->getSize();
            $width = $height = null;
            try {
                [$width, $height] = getimagesize($file->getRealPath()) ?: [null, null];
            } catch (\Throwable $e) {}

            SolicitudAdjunto::create([
                'solicitud_id' => $solicitudId,
                'seccion'      => $seccion, // 'cambio_dice' | 'cambio_debe_decir'
                'path'         => $path,
                'disk'         => 'public',
                'original_name'=> $original,
                'mime'         => $mime,
                'size'         => $size,
                'width'        => $width,
                'height'       => $height,
                'orden'        => $i,
            ]);
        }
    }

    private function generarFolio(): string
    {
        $anio = now()->year;
        $consecutivo = (int) Solicitud::whereYear('fecha', $anio)->count() + 1;
        return sprintf('SGC-%d-%04d', $anio, $consecutivo);
    }

    public function render(): View
    {
        return view('livewire.calidad.solicitudes.crear-solicitud');
    }
}
