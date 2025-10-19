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

    public function updatedAreaId(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openExportModal(): void
    {
        $this->exportDate = $this->computeDefaultExportDate(); // YYYY-MM-DD
        $this->showExportModal = true;
    }

    private function computeDefaultExportDate(): string
    {
        $likeOp = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
        $needle = $this->search !== '' ? '%'.$this->search.'%' : null;

        $maxDate = \App\Models\ListaMaestra::query()
            ->when($this->areaId, fn($q) => $q->where('area_id', $this->areaId))
            ->when($needle, function ($q) use ($likeOp, $needle) {
                $q->where(function ($qq) use ($likeOp, $needle) {
                    $qq->where('codigo', $likeOp, $needle)
                    ->orWhere('nombre', $likeOp, $needle);
                });
            })
            ->max('fecha_autorizacion'); // puede retornar string/Carbon/null según el cast

        return $maxDate
            ? Carbon::parse($maxDate)->toDateString()   // YYYY-MM-DD
            : now()->toDateString();
    }

    public function exportPdf()
    {
        $this->validate([
            'exportDate' => 'required|date|after_or_equal:1900-01-01',
        ]);

        $params = array_filter([
            'area_id' => $this->areaId,
            'q'       => $this->search !== '' ? $this->search : null,
            'date'    => $this->exportDate, // única fecha del encabezado
        ], fn($v) => !is_null($v) && $v !== '');

        return redirect()->route('calidad.lista-maestra.pdf', $params);
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
