<?php

namespace App\Livewire\Calidad\Solicitudes;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Complaint;

class SolicitudesBadge extends Component
{
    public string $scope = 'solicitudes';

    public int $countSolicitudes = 0;
    public int $countComplaints = 0;

    public function mount($scope = 'solicitudes')
    {
        $this->scope = $scope;
        $this->actualizar();
    }

    public function actualizar()
    {
        $user = auth()->user();

        // 1) Solicitudes en revisión
        if ($this->scope === 'solicitudes') {
            if ($user->hasRole('Super Admin') || $user->hasRole('Coordinador de Calidad')) {
                $this->countSolicitudes = Solicitud::where('estado', 'en_revision')->count();
            } else {
                $this->countSolicitudes = Solicitud::where('user_id', $user->id)
                                                ->where('estado', 'en_revision')
                                                ->count();
            }
        }

        // 2) Quejas abiertas (solo admin)
        if ($this->scope === 'complaints') {
            $this->countComplaints = \App\Models\Complaint::where('estado', 'abierta')->count();
        }
    }

}
