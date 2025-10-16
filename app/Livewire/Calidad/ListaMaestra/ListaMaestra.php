<?php

namespace App\Livewire\Calidad\ListaMaestra;

use App\Livewire\PageWithDashboard;
use App\Models\Area;
use App\Models\ListaMaestra as ListaMaestraModel;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ListaMaestra extends PageWithDashboard
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

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
