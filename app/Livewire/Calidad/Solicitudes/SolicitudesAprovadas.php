<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Solicitud;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;

class SolicitudesAprovadas extends PageWithDashboard
{
    public Solicitud $solicitud;

    public function mount(Solicitud $solicitud): void
    {
        // Carga de relaciones para mostrar datos
        $this->solicitud->load([
            'usuario:id,name',
            'area:id,nombre',
            'documento:id,codigo,nombre,revision,fecha_autorizacion',
        ]);
    }

    public function descargarFormato()
    {
        return redirect()->route('calidad.solicitudes.estado.formato.pdf', $this->solicitud->id);
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.calidad.solicitudes.solicitudes-aprovadas', [
            'solicitud' => $this->solicitud,
        ]);
    }
}
