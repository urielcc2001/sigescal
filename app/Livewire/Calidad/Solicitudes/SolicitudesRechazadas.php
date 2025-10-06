<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Historial;
use App\Models\ListaMaestra;
use App\Models\Solicitud;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class SolicitudesRechazadas extends PageWithDashboard
{
    public Solicitud $solicitud;

    // Campos editables
    public string $fecha;
    public ?int $documento_id = null;
    public string $codigo = '';
    public string $tipo = 'modificacion';
    public string $cambio_dice = '';
    public string $cambio_debe_decir = '';
    public string $justificacion = '';
    public bool $requiere_capacitacion = false;
    public bool $requiere_difusion = false;

    // Catálogo (sin relaciones, solo datalist)
    public $documentos;

    // Info del rechazo más reciente (solo lectura)
    public ?string $motivoRechazo = null;
    public ?string $rechazoPor = null;
    public ?string $rechazoFecha = null;

    // Modal de confirmación
    public bool $showConfirmReenviar = false;

    public function rules(): array
    {
        return [
            'fecha'                 => ['required', 'date'],
            'documento_id'         => ['required', 'exists:lista_maestra,id'],
            'tipo'                 => ['required', 'in:creacion,modificacion,baja'],
            'cambio_dice'          => ['required', 'string', 'min:3'],
            'cambio_debe_decir'    => ['required', 'string', 'min:3'],
            'justificacion'        => ['required', 'string', 'min:5'],
            'requiere_capacitacion'=> ['boolean'],
            'requiere_difusion'    => ['boolean'],
        ];
    }

    public function mount(Solicitud $solicitud): void
    {
        // Solo dueño y estado rechazado
        abort_unless($solicitud->user_id === auth()->id(), 403);
        abort_unless($solicitud->estado === 'rechazada', 403);

        $this->solicitud = $solicitud->load(['documento:id,codigo,nombre,revision,area_id']);

        // Prefill
        $this->fecha                = optional($solicitud->fecha)->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->documento_id        = $solicitud->documento_id;
        $this->codigo              = $solicitud->documento->codigo ?? '';
        $this->tipo                = $solicitud->tipo ?? 'modificacion';
        $this->cambio_dice         = $solicitud->cambio_dice ?? '';
        $this->cambio_debe_decir   = $solicitud->cambio_debe_decir ?? '';
        $this->justificacion       = $solicitud->justificacion ?? '';
        $this->requiere_capacitacion = (bool) $solicitud->requiere_capacitacion;
        $this->requiere_difusion     = (bool) $solicitud->requiere_difusion;

        // Catálogo sin relaciones (para datalist)
        $this->documentos = ListaMaestra::select('id','codigo','nombre','revision','area_id')
            ->orderBy('codigo')
            ->get();

        // Último rechazo
        $rej = $solicitud->historial()->where('estado','rechazada')->latest()->with('usuario:id,name')->first();
        if ($rej) {
            $this->motivoRechazo = $rej->comentario;
            $this->rechazoPor    = $rej->usuario->name ?? null;
            $this->rechazoFecha  = $rej->created_at?->format('Y-m-d H:i');
        }
    }

    // Cuando cambia el código, selecciona documento_id
    public function updatedCodigo(): void
    {
        $doc = ListaMaestra::where('codigo', $this->codigo)->first();
        $this->documento_id = $doc?->id;
    }

    public function abrirConfirmarReenviar(): void
    {
        $this->showConfirmReenviar = true;
    }

    public function cerrarConfirmarReenviar(): void
    {
        $this->showConfirmReenviar = false;
    }

    /** Guardar cambios y volver a enviar a revisión */
    public function reenviar()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $sol = Solicitud::whereKey($this->solicitud->id)->lockForUpdate()->firstOrFail();
                abort_unless($sol->user_id === auth()->id(), 403);
                abort_unless($sol->estado === 'rechazada', 403);

                $sol->update([
                    'fecha'                 => $this->fecha,
                    'documento_id'          => $this->documento_id,
                    'tipo'                  => $this->tipo,
                    'cambio_dice'           => $this->cambio_dice,
                    'cambio_debe_decir'     => $this->cambio_debe_decir,
                    'justificacion'         => $this->justificacion,
                    'requiere_capacitacion' => $this->requiere_capacitacion,
                    'requiere_difusion'     => $this->requiere_difusion,
                    'estado'                => 'en_revision',
                ]);

                Historial::create([
                    'solicitud_id' => $sol->id,
                    'estado'       => 'en_revision',
                    'comentario'   => 'Reenvío del solicitante tras correcciones.',
                    'user_id'      => auth()->id(),
                ]);
            });

            $this->cerrarConfirmarReenviar();
            session()->flash('success', 'Solicitud reenviada para revisión.');
            return redirect()->route('calidad.solicitudes.estado');

        } catch (\Throwable $e) {
            report($e);
            $this->cerrarConfirmarReenviar();
            session()->flash('error', 'No se pudo reenviar la solicitud. Intenta nuevamente.');
        }
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        // Documento seleccionado con area cargada (evita lazy loading en la vista)
        $docSel = $this->documento_id
            ? ListaMaestra::with('area:id,nombre')->find($this->documento_id)
            : null;

        return view('livewire.calidad.solicitudes.solicitudes-rechazadas', [
            'docSel' => $docSel,
        ]);
    }
}
