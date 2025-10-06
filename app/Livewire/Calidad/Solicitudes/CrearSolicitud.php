<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Area;
use App\Models\ListaMaestra;
use App\Models\Solicitud;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CrearSolicitud extends PageWithDashboard
{
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

    /**
     * Al cambiar el documento, autocompleta el área (desde la lista maestra).
     */

    public function updatedCodigo($value): void
    {
        $doc = $this->documentos->firstWhere('codigo', $value);

        if ($doc) {
            $this->documento_id = $doc->id;
            $this->area_id = $doc->area_id;
        } else {
            // si no coincide con ningún documento conocido
            $this->documento_id = null;
            $this->area_id = null;
        }
    }


    protected function rules(): array
    {
        return [
            'folio' => ['required','string','max:50'],
            'fecha' => ['required','date'],
            'documento_id' => ['nullable','exists:lista_maestra,id'],
            'area_id' => ['nullable','exists:areas,id'],
            'tipo' => ['required','in:creacion,modificacion,baja'],
            'cambio_dice' => ['nullable','string'],
            'cambio_debe_decir' => ['nullable','string'],
            'justificacion' => ['required','string','min:5'],
            'requiere_capacitacion' => ['boolean'],
            'requiere_difusion' => ['boolean'],
        ];
    }

    public function save()
    {
        $this->validate();

        $solicitud = Solicitud::create([
            'folio' => $this->folio,
            'fecha' => $this->fecha,
            'documento_id' => $this->documento_id,
            'area_id' => $this->area_id ?? Auth::user()->area_id ?? null,
            'user_id' => Auth::id(),
            'tipo' => $this->tipo,
            'cambio_dice' => $this->cambio_dice,
            'cambio_debe_decir' => $this->cambio_debe_decir,
            'justificacion' => $this->justificacion,
            'requiere_capacitacion' => $this->requiere_capacitacion,
            'requiere_difusion' => $this->requiere_difusion,
            'estado' => 'en_revision',
        ]);

        // Generar nuevo folio para la siguiente captura
        $this->resetExcept(['documentos','areas','tipos']);
        $this->fecha = now()->toDateString();
        $this->folio = $this->generarFolio();
        $this->requiere_difusion = true;

        session()->flash('success', 'Solicitud enviada correctamente (Folio: '.$solicitud->folio.')');
        // Opcional: redirigir a listado/estado
        return redirect()->route('calidad.solicitudes.estado');
    }

    private function generarFolio(): string
    {
        // Ejemplo: SGC-2025-0001 (incremental por año)
        $año = now()->year;
        $consecutivo = (int) Solicitud::whereYear('fecha', $año)->count() + 1;
        return sprintf('SGC-%d-%04d', $año, $consecutivo);
    }

    public function render(): View
    {
        return view('livewire.calidad.solicitudes.crear-solicitud');
    }
}
