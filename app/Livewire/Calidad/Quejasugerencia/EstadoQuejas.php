<?php

namespace App\Livewire\Calidad\Quejasugerencia;

use App\Livewire\PageWithDashboard;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class EstadoQuejas extends PageWithDashboard
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    // Modal
    public bool $showView = false;
    public ?int $selectedId = null;

    protected $queryString = [
        'search'  => ['except' => ''],
        'page'    => ['except' => 1],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function getRowsProperty()
    {
        $alumno = Auth::guard('students')->user();

        return Complaint::query()
            ->where('student_id', $alumno->id)
            ->when($this->search !== '', function ($q) {
                $term = '%' . trim($this->search) . '%';
                $q->where(function ($qq) use ($term) {
                    $qq->where('folio', 'like', $term)
                       ->orWhere('tipo', 'like', $term)
                       ->orWhere('estado', 'like', $term)
                       ->orWhere('descripcion', 'like', $term);
                });
            })
            ->orderByDesc('created_at')
            ->paginate($this->perPage);
    }

    public function view(int $id): void
    {
        // Solo guardamos el ID; el modelo lo resolvemos con un Computed
        $this->selectedId = $id;
        $this->showView   = true;
    }

    public function closeView(): void
    {
        $this->reset(['showView', 'selectedId']);
    }

    #[Computed]
    public function selected(): ?Complaint
    {
        if (!$this->selectedId) return null;

        $alumnoId = Auth::guard('students')->id();

        return Complaint::query()
            ->where('student_id', $alumnoId)
            ->find($this->selectedId);
    }

    public function render()
    {
        return view('livewire.calidad.quejasugerencia.estado-quejas', [
            'rows' => $this->rows,
        ]);
    }
}
