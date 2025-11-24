<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Solicitud;
use App\Models\Historial;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class VerSolicitud extends PageWithDashboard
{
    use LivewireAlert;
    public ?Solicitud $solicitud = null;

    // Modales
    public bool $showApproveModal = false;
    public bool $showRejectModal  = false;

    // Comentarios
    public string $comentarioAprobacion = '';
    public string $motivoRechazo = '';

    public function mount(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud->load([
            'usuario:id,name',
            'area:id,nombre',
            'documento:id,codigo,nombre,revision,fecha_autorizacion',
            'historial.usuario:id,name',
            // nuevas relaciones para mostrar imágenes
            'imagenesDice',
            'imagenesDebeDecir',
        ]);
    }

    public function abrirAprobar()
    {
        if ($this->solicitud->estado !== 'en_revision') return;
        $this->showApproveModal = true;
    }

    public function abrirRechazar()
    {
        if ($this->solicitud->estado !== 'en_revision') return;
        $this->showRejectModal = true;
    }

    public function aprobar()
    {
        if ($this->solicitud->estado !== 'en_revision') return;

        DB::transaction(function () {

            // 1) Impacto en lista maestra según tipo
            if ($this->solicitud->tipo === 'modificacion' && $this->solicitud->documento) {

                // MODIFICACIÓN: incrementa revisión del documento existente
                $this->solicitud->documento->increment('revision');
                $this->solicitud->documento->update([
                    'fecha_autorizacion' => now(),
                ]);

            } elseif ($this->solicitud->tipo === 'creacion' && ! $this->solicitud->documento_id) {

                // CREACIÓN: crear nuevo registro en Lista Maestra
                $nuevo = \App\Models\ListaMaestra::create([
                    'codigo'   => $this->solicitud->codigo_nuevo,
                    'nombre'   => $this->solicitud->titulo_nuevo,
                    'revision' => $this->solicitud->revision_nueva,
                    'area_id'  => $this->solicitud->area_id,
                    'fecha_autorizacion' => now(),
                ]);

                // Enlazar solicitud con el documento recién creado
                $this->solicitud->update([
                    'documento_id' => $nuevo->id,
                ]);

                // Opcional: actualizar la relación en memoria (no toca BD)
                $this->solicitud->setRelation('documento', $nuevo);
            }

            // 2) Historial
            Historial::create([
                'solicitud_id' => $this->solicitud->id,
                'estado'       => 'aprobada',
                'comentario'   => $this->comentarioAprobacion ?: null,
                'user_id'      => auth()->id(),
            ]);

            // 3) Estado actual en Solicitud
            $this->solicitud->update(['estado' => 'aprobada']);
        });

        // Opcional: recargar modelo por si se usa después en la misma vista
        $this->solicitud->refresh();

        $this->showApproveModal = false;
        $this->flash('success', 'Solicitud aprobada.');
        return redirect()->route('calidad.solicitudes.revisar');
    }

    public function rechazar()
    {
        if ($this->solicitud->estado !== 'en_revision') return;

        $this->validate([
            'motivoRechazo' => 'required|string|min:5'
        ]);

        DB::transaction(function () {
            // 1) Historial con motivo
            Historial::create([
                'solicitud_id' => $this->solicitud->id,
                'estado'       => 'rechazada',
                'comentario'   => $this->motivoRechazo,
                'user_id'      => auth()->id(),
            ]);

            // 2) Estado actual
            $this->solicitud->update(['estado' => 'rechazada']);
        });

        $this->showRejectModal = false;
        $this->flash('success', 'Solicitud rechazada.');
        return redirect()->route('calidad.solicitudes.revisar');
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.calidad.solicitudes.ver-solicitud', [
            'solicitud' => $this->solicitud,
            // traer historial ordenado reciente→antiguo
            'historial' => $this->solicitud->historial()->with('usuario:id,name')->get(),
        ]);
    }
}
