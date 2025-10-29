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

    // Vista: por revisar | revisadas
    public string $vista = 'por_revisar';

    // Filtros de fecha (YYYY-MM-DD)
    public ?string $fecha_inicio = null;
    public ?string $fecha_fin = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'fecha'],
        'sortDirection' => ['except' => 'desc'],
        'vista'         => ['except' => 'por_revisar'],
        'fecha_inicio'  => ['except' => null],
        'fecha_fin'     => ['except' => null],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedPerPage(): void { $this->resetPage(); }
    public function updatedVista(): void { $this->resetPage(); }
    public function updatedFechaInicio(): void { $this->resetPage(); }
    public function updatedFechaFin(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
        $this->resetPage();
    }

    // Limpia rango de fechas (opcional)
    public function clearDates(): void
    {
        $this->fecha_inicio = null;
        $this->fecha_fin = null;
        $this->resetPage();
    }

    /** Dataset principal */
    public function getRowsProperty()
    {
        return Solicitud::query()
            ->with([
                'usuario:id,name',
                'documento:id,codigo,nombre',
            ])
            ->when($this->vista === 'por_revisar', fn($q) =>
                $q->where('estado', 'en_revision')
            )
            ->when($this->vista === 'revisadas', fn($q) =>
                $q->whereIn('estado', ['aprobada', 'rechazada'])
            )
            // Búsqueda
            ->when($this->search !== '', function ($q) {
                $term = "%{$this->search}%";
                $q->where(function ($qq) use ($term) {
                    $qq->where('folio', 'like', $term)
                       ->orWhere('cambio_dice', 'like', $term)
                       ->orWhere('cambio_debe_decir', 'like', $term)
                       ->orWhere('justificacion', 'like', $term)
                       ->orWhereHas('usuario', fn($s) => $s->where('name', 'like', $term))
                       ->orWhereHas('documento', function ($d) use ($term) {
                           $d->where('nombre', 'like', $term)
                             ->orWhere('codigo', 'like', $term);
                       });
                });
            })

            // Rango de fechas (columna 'fecha')
            ->when($this->fecha_inicio, fn($q) =>
                $q->whereDate('fecha', '>=', $this->fecha_inicio)
            )
            ->when($this->fecha_fin, fn($q) =>
                $q->whereDate('fecha', '<=', $this->fecha_fin)
            )

            // Orden
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.calidad.solicitudes.revisar-solicitudes', [
            'rows' => $this->rows,
        ]);
    }
}
