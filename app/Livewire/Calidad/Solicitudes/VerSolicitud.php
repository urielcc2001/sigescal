<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Solicitud;
use App\Models\Historial;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class VerSolicitud extends PageWithDashboard
{
    public Solicitud $solicitud;

    // Modales
    public bool $showApproveModal = false;
    public bool $showRejectModal  = false;

    // Comentarios
    public string $comentarioAprobacion = '';
    public string $motivoRechazo = '';

    public function mount(Solicitud $solicitud)
    {
        $this->solicitud->load([
            'usuario:id,name',
            'area:id,nombre',
            'documento:id,codigo,nombre,revision,fecha_autorizacion',
            'historial.usuario:id,name',
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
            // 1) (Opcional) Impacto en lista maestra si aplica
            if ($this->solicitud->documento && $this->solicitud->tipo === 'modificacion') {
                 $this->solicitud->documento->increment('revision');
                 $this->solicitud->documento->update(['fecha_autorizacion' => now()]);
            }

            // 2) Escribir historial
            Historial::create([
                'solicitud_id' => $this->solicitud->id,
                'estado'       => 'aprobada',
                'comentario'   => $this->comentarioAprobacion ?: null, // opcional
                'user_id'      => auth()->id(),
            ]);

            // 3) Estado actual en principal
            $this->solicitud->update(['estado' => 'aprobada']);
        });

        $this->showApproveModal = false;
        session()->flash('success', 'Solicitud aprobada.');
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
        session()->flash('success', 'Solicitud rechazada.');
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
