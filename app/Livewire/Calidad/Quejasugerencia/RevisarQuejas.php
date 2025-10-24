<?php

namespace App\Livewire\Calidad\Quejasugerencia;

use App\Livewire\PageWithDashboard;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class RevisarQuejas extends PageWithDashboard
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    // Filtros simples
    public ?string $fEstado = null;     // abierta | en_proceso | respondida | cerrada
    public ?string $fTipo = null;       // queja | sugerencia

    // Modal
    public bool $showView = false;
    public ?int $selectedId = null;

    // Campo de respuesta
    #[Validate('required|string|min:5|max:4000')]
    public string $respuestaText = '';

    protected $queryString = [
        'search'  => ['except' => ''],
        'page'    => ['except' => 1],
        'perPage' => ['except' => 10],
        'fEstado' => ['except' => null],
        'fTipo'   => ['except' => null],
    ];

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
            ->orderByDesc('created_at')
            ->paginate($this->perPage);
    }

    public function view(int $id): void
    {
        $this->selectedId   = $id;
        $this->showView     = true;
        $this->respuestaText = (string) ($this->selected?->respuesta ?? '');
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
        // $this->dispatch('toast', type: 'info', message: 'Marcada en proceso'); // si usas toasts
    }

    /** Guardar respuesta y marcar como respondida */
    public function respond(): void
    {
        $this->validateOnly('respuestaText');

        $row = Complaint::findOrFail($this->selectedId);
        $row->respuesta     = $this->respuestaText;
        $row->respondida_at = now();

        // Si está abierta o en_proceso, pasamos a respondida
        if (in_array($row->estado, ['abierta','en_proceso'])) {
            $row->estado = 'respondida';
        }

        // Si quieres registrar quién respondió (si tienes columna), descomenta y ajusta:
        // $row->respondida_por = Auth::id();

        $row->save();

        // Cerrar modal y refrescar
        $this->closeView();
        // $this->dispatch('toast', type: 'success', message: 'Respuesta enviada');
    }

    /** Cerrar definitivamente (opcional) */
    public function closeTicket(int $id): void
    {
        $row = Complaint::findOrFail($id);
        $row->estado = 'cerrada';
        $row->save();
    }

    public function render()
    {
        return view('livewire.calidad.quejasugerencia.revisar-quejas', [
            'rows' => $this->rows,
        ]);
    }
}
