<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Solicitud;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;

class EstadoSolicitud extends PageWithDashboard
{
    use WithPagination;

    public string $search = '';
    public ?string $estado = null;   // null = todos | en_revision | aprobada | rechazada
    public int $perPage = 10;

    public string $sortField = 'fecha';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'estado' => ['except' => null],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'fecha'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedEstado(): void { $this->resetPage(); }
    public function updatedPerPage(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    /** Dataset: solicitudes del usuario autenticado */
    public function getRowsProperty()
    {
        return Solicitud::query()
            ->with(['documento:id,codigo,nombre'])
            ->where('user_id', auth()->id())
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('folio', 'like', "%{$this->search}%")
                      ->orWhere('cambio_dice', 'like', "%{$this->search}%")
                      ->orWhere('cambio_debe_decir', 'like', "%{$this->search}%")
                      ->orWhere('justificacion', 'like', "%{$this->search}%")
                      ->orWhereHas('documento', function ($qq) {
                          $qq->where('nombre', 'like', "%{$this->search}%")
                             ->orWhere('codigo', 'like', "%{$this->search}%");
                      });
                });
            })
            ->when($this->estado, fn($q) => $q->where('estado', $this->estado))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render(): View
    {
        return view('livewire.calidad.solicitudes.estado-solicitud', [
            'rows' => $this->rows,
        ]);
    }
}
