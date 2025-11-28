<?php

namespace App\Livewire\Calidad\Quejasugerencia;

use App\Livewire\PageWithDashboard;
use App\Models\Complaint;
use Livewire\Attributes\Computed;

class EstadoQuejas extends PageWithDashboard
{
    public string $folio = '';

    public ?int $selectedId = null;
    public bool $showView = false;

    public ?Complaint $found = null;

    public function buscar(): void
    {
        $this->resetErrorBag();

        if (trim($this->folio) === '') {
            $this->addError('folio', 'Debe ingresar un folio.');
            return;
        }

        $this->found = Complaint::where('folio', trim($this->folio))->first();

        if (!$this->found) {
            $this->addError('folio', 'No se encontró ninguna queja o sugerencia con ese folio.');
        }
    }

    public function view(int $id): void
    {
        $this->selectedId = $id;
        $this->showView = true;
    }

    public function closeView(): void
    {
        $this->reset(['showView', 'selectedId']);
    }

    #[Computed]
    public function selected(): ?Complaint
    {
        if (!$this->selectedId) return null;

        return Complaint::find($this->selectedId);
    }

    public function render()
    {
        return view('livewire.calidad.quejasugerencia.estado-quejas', [
            'found' => $this->found,
        ]);
    }
}
