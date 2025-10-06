<?php

namespace App\Livewire\Calidad\Solicitudes;

use App\Livewire\PageWithDashboard;
use App\Models\Solicitud;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class RevisarSolicitudes extends PageWithDashboard
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public string $sortField = 'fecha';
    public string $sortDirection = 'desc';

    // para cuando implementes el detalle
    public ?int $selectedId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'fecha'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
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

    /** Dataset principal SOLO en_revision */
    public function getRowsProperty()
    {
        return Solicitud::query()
            ->with([
                'usuario:id,name',
                'documento:id,codigo,nombre',
            ])
            ->where('estado', 'en_revision')
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('folio', 'like', "%{$this->search}%")
                      ->orWhere('cambio_dice', 'like', "%{$this->search}%")
                      ->orWhere('cambio_debe_decir', 'like', "%{$this->search}%")
                      ->orWhere('justificacion', 'like', "%{$this->search}%")
                      ->orWhereHas('usuario', fn($qq) => $qq->where('name', 'like', "%{$this->search}%"))
                      ->orWhereHas('documento', fn($qq) => $qq->where('nombre', 'like', "%{$this->search}%")
                                                             ->orWhere('codigo', 'like', "%{$this->search}%"));
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    /** Placeholder para el futuro detalle */
    public function ver(int $id): void
    {
        $this->selectedId = $id;
        // Aquí luego mostraremos el detalle/acciones.
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.calidad.solicitudes.revisar-solicitudes', [
            'rows' => $this->rows,
        ]);
    }
}
