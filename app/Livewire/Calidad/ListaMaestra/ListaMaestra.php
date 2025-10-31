<?php

namespace App\Livewire\Calidad\ListaMaestra;

use App\Livewire\PageWithDashboard;
use App\Models\Area;
use App\Models\ListaMaestra as ListaMaestraModel;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ListaMaestra extends PageWithDashboard
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public bool $showExportModal = false;
    public ?string $exportDate = null;

    /** Filtros */
    public ?int $areaId = null;
    public string $search = '';

    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    /** Registro en edición/eliminación */
    public ?int $editingId = null;
    public ?int $deletingId = null;

    /** Form edición */
    public string $codigo = '';
    public string $nombre = '';
    public string $revision = '';
    public ?string $fecha_autorizacion = null; 
    public ?string $deletingLabel = null;

    // ======== Lifecycle de filtros ========
    public function updatedAreaId(): void { $this->resetPage(); }
    public function updatedSearch(): void { $this->resetPage(); }

    public function openExportModal(): void
    {
        $this->exportDate = $this->computeDefaultExportDate(); // YYYY-MM-DD
        $this->showExportModal = true;
    }

    private function computeDefaultExportDate(): string
    {
        $likeOp = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
        $needle = $this->search !== '' ? '%'.$this->search.'%' : null;

        $maxDate = ListaMaestraModel::query()
            ->when($this->areaId, fn($q) => $q->where('area_id', $this->areaId))
            ->when($needle, function ($q) use ($likeOp, $needle) {
                $q->where(function ($qq) use ($likeOp, $needle) {
                    $qq->where('codigo', $likeOp, $needle)
                       ->orWhere('nombre', $likeOp, $needle);
                });
            })
            ->max('fecha_autorizacion');

        return $maxDate ? Carbon::parse($maxDate)->toDateString() : now()->toDateString();
    }

    public function exportPdf()
    {
        $this->validate([
            'exportDate' => 'required|date|after_or_equal:1900-01-01',
        ]);

        $params = array_filter([
            'area_id' => $this->areaId,
            'q'       => $this->search !== '' ? $this->search : null,
            'date'    => $this->exportDate,
        ], fn($v) => !is_null($v) && $v !== '');

        return redirect()->route('calidad.lista-maestra.pdf', $params);
    }

    // ======== Reglas de validación para edición ========
    protected function rules(): array
    {
        return [
            'codigo'             => 'required|string|max:50',
            'nombre'             => 'required|string|max:255',
            'revision'           => 'required|string|max:50',
            'fecha_autorizacion' => 'nullable|date|after_or_equal:1900-01-01',
        ];
    }

    // ======== Edición ========
    public function openEdit(int $id): void
    {
        $doc = ListaMaestraModel::findOrFail($id);

        $this->editingId = $doc->id;
        $this->codigo = (string) $doc->codigo;
        $this->nombre = (string) $doc->nombre;
        $this->revision = (string) $doc->revision;

        // Normaliza a 'Y-m-d' para el input date; si es null -> null
        $this->fecha_autorizacion = $doc->fecha_autorizacion
            ? Carbon::parse($doc->fecha_autorizacion)->toDateString()
            : null;

        $this->showEditModal = true;
    }

    public function saveEdit(): void
    {
        $this->validate();

        if (!$this->editingId) return;

        $doc = ListaMaestraModel::findOrFail($this->editingId);

        $doc->update([
            'codigo'             => $this->codigo,
            'nombre'             => $this->nombre,
            'revision'           => $this->revision,
            'fecha_autorizacion' => $this->fecha_autorizacion ?: null,
        ]);

        $this->showEditModal = false;
        $this->dispatch('toastifyAlert', [
            'message' => 'Documento actualizado correctamente.',
            'type'    => 'success',
        ]);

        // Opcional: limpiar estado de edición
        $this->reset(['editingId', 'codigo', 'nombre', 'revision', 'fecha_autorizacion']);
    }

    // ======== Eliminación ========
    public function confirmDelete(int $id): void
    {
        $doc = \App\Models\ListaMaestra::findOrFail($id);
        $this->deletingId = $id;
        $this->deletingLabel = "{$doc->codigo} — {$doc->nombre}";
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (!$this->deletingId) return;

        $doc = ListaMaestraModel::findOrFail($this->deletingId);

        try {
            $doc->delete();
            $this->dispatch('toastifyAlert', [
                'message' => 'Documento eliminado.',
                'type'    => 'success',
            ]);
        } catch (\Throwable $e) {
            // Por si hay FK con solicitudes, etc.
            $this->dispatch('toastifyAlert', [
                'message' => 'No se pudo eliminar: el documento está en uso.',
                'type'    => 'error',
            ]);
        }

        $this->showDeleteModal = false;
        $this->reset(['deletingId']);
        // Mantiene página/filters; Livewire refresca la tabla automáticamente
    }

    public function render(): View
    {
        $areas = Area::select('id', 'nombre')->orderBy('nombre')->get();

        $likeOp = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
        $needle = '%'.$this->search.'%';

        $docs = ListaMaestraModel::select('id', 'codigo', 'nombre', 'revision', 'fecha_autorizacion', 'area_id')
            ->when($this->areaId, fn ($q) => $q->where('area_id', $this->areaId))
            ->when($this->search !== '', function ($q) use ($likeOp, $needle) {
                $q->where(function ($qq) use ($likeOp, $needle) {
                    $qq->where('codigo', $likeOp, $needle)
                       ->orWhere('nombre', $likeOp, $needle);
                });
            })
            ->orderBy('codigo')
            ->paginate(15);

        return view('livewire.calidad.lista-maestra.lista-maestra', [
            'areas' => $areas,
            'docs'  => $docs,
        ]);
    }
}
