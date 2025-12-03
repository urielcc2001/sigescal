<?php

namespace App\Livewire\Calidad\Quejasugerencia;

use App\Livewire\PageWithDashboard;
use App\Models\Complaint;
use App\Models\OrgPosition;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RevisarQuejas extends PageWithDashboard
{
    use WithPagination;
    use LivewireAlert;

    public string $search = '';
    public int $perPage = 10;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Filtros simples
    public ?string $fEstado = null;     // abierta | en_proceso | respondida | cerrada
    public ?string $fTipo = null;       // queja | sugerencia

    // Modal
    public bool $showView = false;
    public ?int $selectedId = null;

    // Campo de respuesta
    #[Validate('required|string|min:5|max:4000')]
    public string $respuestaText = '';

    // NUEVO: selector de subdirector
    public ?string $subdirector_slug = null;

    public array $subdirectoresLabels = [];


    protected $queryString = [
        'search'  => ['except' => ''],
        'page'    => ['except' => 1],
        'perPage' => ['except' => 10],
        'fEstado' => ['except' => null],
        'fTipo'   => ['except' => null],
    ];


    public function mount()
    {
        $this->loadSubdirectores();
    }

    protected function loadSubdirectores(): void
    {
        // Usa los mismos slugs que en SolicitudPdfController::subdirectorSlugPorCodigo
        $slugs = [
            'subdir-academica',
            'subdir-vinculacion',
            'subdir-servicios',
        ];

        $positions = OrgPosition::with('vigente')
            ->whereIn('slug', $slugs)
            ->get();

        $this->subdirectoresLabels = [];

        foreach ($positions as $pos) {
            $titular = optional($pos->vigente)->nombre;  // PERSONA
            $puesto = mb_strtoupper($pos->titulo, 'UTF-8');

            if ($titular) {
                $this->subdirectoresLabels[$pos->slug] = "{$titular} ({$puesto})";
            } else {
                $this->subdirectoresLabels[$pos->slug] = "{$puesto} (S/F)";
            }
        }
    }

    public function updatingSearch()   { $this->resetPage(); }
    public function updatedFEstado()   { $this->resetPage(); }
    public function updatedFTipo()     { $this->resetPage(); }
    public function updatedPerPage()   { $this->resetPage(); }

    public function getRowsProperty()
    {
        return Complaint::query()
            ->with(['student']) // para mostrar numcontrol sin N+1
            ->when($this->search !== '', function ($q) {
                $term = '%' . trim($this->search) . '%';
                $q->where(function ($qq) use ($term) {
                    $qq->where('folio', 'like', $term)
                       ->orWhere('tipo', 'like', $term)
                       ->orWhere('estado', 'like', $term)
                       ->orWhere('descripcion', 'like', $term)
                       ->orWhereHas('student', fn($s) => $s->where('numcontrol', 'like', $term));
                });
            })
            ->when($this->fEstado, fn($q) => $q->where('estado', $this->fEstado))
            ->when($this->fTipo, fn($q) => $q->where('tipo', $this->fTipo))
            ->orderBy($this->sortField, $this->sortDirection) 
            ->paginate($this->perPage);
    }

    public function view(int $id): void
    {
        $this->selectedId   = $id;
        $this->showView     = true;
        $this->respuestaText = (string) ($this->selected?->respuesta ?? '');
        $this->subdirector_slug = $this->selected?->subdirector_slug;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }

        if (method_exists($this, 'resetPage')) $this->resetPage();
    }

    public function closeView(): void
    {
        $this->reset(['showView', 'selectedId', 'respuestaText']);
    }

    #[Computed]
    public function selected(): ?Complaint
    {
        if (!$this->selectedId) return null;

        return Complaint::query()
            ->with('student')
            ->find($this->selectedId);
    }

    /** Cambia estado a 'en_proceso' (opcional) */
    public function markInProcess(int $id): void
    {
        $row = Complaint::findOrFail($id);
        $row->estado = 'en_proceso';
        $row->save();
        $this->alert('info', "La queja {$row->folio} fue marcada como EN PROCESO.", [
            'position' => 'top-end',
            'timer'    => 3000,
            'toast'    => true,
        ]);    
    }

    /** Guardar respuesta y marcar como respondida */
    public function respond(): void
    {
        $this->validate([
            'respuestaText'    => 'required|string|min:5|max:4000',
            'subdirector_slug' => 'required|string',
        ], [
            'subdirector_slug.required' => 'Selecciona el subdirector correspondiente.',
        ]);

        $row = Complaint::findOrFail($this->selectedId);

        $row->respuesta        = $this->respuestaText;
        $row->respondida_at    = now();
        $row->subdirector_slug = $this->subdirector_slug;

        if (in_array($row->estado, ['abierta', 'en_proceso'])) {
            $row->estado = 'respondida';
        }

        $row->save();

        $this->closeView();

                $this->alert('success', "Respuesta registrada para la queja {$row->folio}.", [
            'position' => 'top-end',
            'timer'    => 3500,
            'toast'    => true,
        ]);
    }

    /** Cerrar definitivamente (opcional) */
    public function closeTicket(int $id): void
    {
        $row = Complaint::findOrFail($id);
        $row->estado = 'cerrada';
        $row->save();

        $this->alert('success', "La queja {$row->folio} fue cerrada.", [
            'position' => 'top-end',
            'timer'    => 3000,
            'toast'    => true,
        ]);
    }

    public function render()
    {
        return view('livewire.calidad.quejasugerencia.revisar-quejas', [
            'rows' => $this->rows,
        ]);
    }
}
