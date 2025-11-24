<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Historial;
use App\Models\ListaMaestra;
use App\Models\Solicitud;
use App\Models\SolicitudAdjunto;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Area;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SolicitudesRechazadas extends PageWithDashboard
{
    use WithFileUploads;
    use LivewireAlert;

    public Solicitud $solicitud;

    // Campos editables
    public string $fecha;
    public ?int $documento_id = null;
    public ?int $area_id = null;
    public ?string $revision_actual = null;
    public string $titulo = '';
    public $areas;
    public string $codigo = '';
    public string $tipo = 'modificacion';
    public string $cambio_dice = '';
    public string $cambio_debe_decir = '';
    public string $justificacion = '';
    public bool $requiere_capacitacion = false;
    public bool $requiere_difusion = false;

    // Nuevos: archivos en memoria (no guardados aún)
    public array $imagenesDice = [];
    public array $imagenesDebeDecir = [];

    // Adjuntos existentes (desde BD)
    public $adjuntosDice = [];
    public $adjuntosDebeDecir = [];

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
        $rules = [
            'fecha'                  => ['required', 'date'],
            'tipo'                   => ['required', 'in:creacion,modificacion,baja'],
            'cambio_dice'            => ['required', 'string', 'min:3'],
            'cambio_debe_decir'      => ['required', 'string', 'min:3'],
            'justificacion'          => ['required', 'string', 'min:5'],
            'requiere_capacitacion'  => ['boolean'],
            'requiere_difusion'      => ['boolean'],
            'imagenesDice.*'         => ['image','max:2048'],
            'imagenesDebeDecir.*'    => ['image','max:2048'],
        ];

        if ($this->tipo === 'creacion') {
            $rules['area_id']          = ['required','exists:areas,id'];
            $rules['codigo']           = ['required','string','max:255'];
            $rules['revision_actual']  = ['required','string','max:10'];
            $rules['titulo']           = ['required','string','max:255'];
        } else {
            $rules['documento_id']     = ['required','exists:lista_maestra,id'];
        }

        return $rules;
    }

    public function mount(Solicitud $solicitud): void
    {
        // Solo dueño y estado rechazado
        abort_unless($solicitud->user_id === auth()->id(), 403);
        abort_unless($solicitud->estado === 'rechazada', 403);

        $this->solicitud = $solicitud->load([
            'documento:id,codigo,nombre,revision,area_id',
            'imagenesDice',          // adjuntos existentes
            'imagenesDebeDecir',
        ]);

        // Prefill
        $this->fecha                  = optional($solicitud->fecha)->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->tipo                   = $solicitud->tipo ?? 'modificacion';
        $this->cambio_dice            = $solicitud->cambio_dice ?? '';
        $this->cambio_debe_decir      = $solicitud->cambio_debe_decir ?? '';
        $this->justificacion          = $solicitud->justificacion ?? '';
        $this->requiere_capacitacion  = (bool) $solicitud->requiere_capacitacion;
        $this->requiere_difusion      = (bool) $solicitud->requiere_difusion;
        $this->area_id                = $solicitud->area_id;

        // Campos según tipo
        if ($this->tipo === 'creacion') {
            // viene de los campos nuevos de la solicitud
            $this->codigo           = $solicitud->codigo_nuevo ?? '';
            $this->revision_actual  = $solicitud->revision_nueva ?? '00';
            $this->titulo           = $solicitud->titulo_nuevo ?? '';
            $this->documento_id     = null; // no hay documento aún
        } else {
            // modificación/baja → usa documento existente
            $this->documento_id     = $solicitud->documento_id;
            $this->codigo           = $solicitud->documento->codigo ?? '';
            $this->revision_actual  = $solicitud->documento->revision ?? null;
            $this->titulo           = $solicitud->documento->nombre ?? '';
        }

        // Catálogo para datalist
        $this->documentos = ListaMaestra::select('id','codigo','nombre','revision','area_id')
            ->orderBy('codigo')
            ->get();

        // Catálogo de áreas (para modo creación)
        $this->areas = Area::select('id','nombre','codigo')->orderBy('nombre')->get();

        // Cargar adjuntos existentes en arrays simples (para blade)
        $this->adjuntosDice      = $this->solicitud->imagenesDice->all();
        $this->adjuntosDebeDecir = $this->solicitud->imagenesDebeDecir->all();

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
        if ($this->tipo === 'creacion') {
            return;
        }

        $doc = ListaMaestra::where('codigo', $this->codigo)->first();
        $this->documento_id    = $doc?->id;
        $this->revision_actual = $doc?->revision;
        $this->titulo          = $doc?->nombre;
        $this->area_id         = $doc?->area_id;
    }

    // quitar una imagen nueva (pre-subida) por índice
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

    // eliminar un adjunto ya guardado (BD + archivo)
    public function deleteAdjunto(int $adjuntoId): void
    {
        $adj = SolicitudAdjunto::where('id', $adjuntoId)
            ->where('solicitud_id', $this->solicitud->id)
            ->first();

        if (!$adj) return;

        // borra físicamente y luego la fila
        try {
            \Storage::disk($adj->disk)->delete($adj->path);
        } catch (\Throwable $e) {
            // no detener si falla la eliminación física
        }
        $seccion = $adj->seccion;
        $adj->delete();

        // refrescar listas locales
        $this->solicitud->refresh()->load(['imagenesDice','imagenesDebeDecir']);
        $this->adjuntosDice      = $this->solicitud->imagenesDice->all();
        $this->adjuntosDebeDecir = $this->solicitud->imagenesDebeDecir->all();

        // mantener los temporales como estaban
        $this->dispatch('notify', type: 'success', message: 'Adjunto eliminado.');
    }

    public function abrirConfirmarReenviar(): void
    {
        $this->showConfirmReenviar = true;
    }
    public function cerrarConfirmarReenviar(): void
    {
        $this->showConfirmReenviar = false;
    }

    /** Guardar cambios + adjuntos y volver a enviar a revisión */
    public function reenviar()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $sol = Solicitud::whereKey($this->solicitud->id)->lockForUpdate()->firstOrFail();
                abort_unless($sol->user_id === auth()->id(), 403);
                abort_unless($sol->estado === 'rechazada', 403);

                // 1) Actualiza campos
                if ($this->tipo === 'creacion') {
                    // Reenvío de solicitud de CREACIÓN
                    $sol->update([
                        'fecha'                 => $this->fecha,
                        'tipo'                  => $this->tipo,
                        'cambio_dice'           => $this->cambio_dice,
                        'cambio_debe_decir'     => $this->cambio_debe_decir,
                        'justificacion'         => $this->justificacion,
                        'requiere_capacitacion' => $this->requiere_capacitacion,
                        'requiere_difusion'     => $this->requiere_difusion,
                        'estado'                => 'en_revision',
                        'area_id'               => $this->area_id,
                        'documento_id'          => null,
                        'codigo_nuevo'          => $this->codigo,
                        'titulo_nuevo'          => $this->titulo,
                        'revision_nueva'        => $this->revision_actual,
                    ]);
                } else {
                    // Reenvío de MODIFICACIÓN / BAJA
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
                }

                // 2) Guardar nuevas imágenes (si las hay)
                $this->guardarAdjuntos($sol->id, 'cambio_dice', $this->imagenesDice);
                $this->guardarAdjuntos($sol->id, 'cambio_debe_decir', $this->imagenesDebeDecir);

                // 3) Historial
                Historial::create([
                    'solicitud_id' => $sol->id,
                    'estado'       => 'en_revision',
                    'comentario'   => 'Reenvío del solicitante tras correcciones.',
                    'user_id'      => auth()->id(),
                ]);
            });

            // limpiar temporales, cerrar modal y redirigir
            $this->imagenesDice = [];
            $this->imagenesDebeDecir = [];

            $this->flash('success', 'Solicitud reenviada para revisión.');
            return redirect()->route('calidad.solicitudes.estado');


        } catch (\Throwable $e) {
            report($e);
            $this->cerrarConfirmarReenviar();
            session()->flash('error', 'No se pudo reenviar la solicitud. Intenta nuevamente.');
        }
    }

    private function guardarAdjuntos(int $solicitudId, string $seccion, array $files): void
    {
        foreach ($files as $i => $file) {
            $path = $file->storePublicly("solicitudes/{$solicitudId}/{$seccion}", 'public');

            $original = $file->getClientOriginalName();
            $mime     = $file->getMimeType();
            $size     = $file->getSize();

            $width = $height = null;
            try {
                [$width, $height] = getimagesize($file->getRealPath()) ?: [null, null];
            } catch (\Throwable $e) {}

            SolicitudAdjunto::create([
                'solicitud_id' => $solicitudId,
                'seccion'      => $seccion,
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

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        // Documento seleccionado con area cargada (evita lazy loading en la vista)
        $docSel = $this->documento_id
            ? ListaMaestra::with('area:id,nombre')->find($this->documento_id)
            : null;

        return view('livewire.calidad.solicitudes.solicitudes-rechazadas', [
            'docSel' => $docSel,
            'areas'  => $this->areas,
        ]);
    }
}
