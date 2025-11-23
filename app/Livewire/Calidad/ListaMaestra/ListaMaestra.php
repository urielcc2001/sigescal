<?php

namespace App\Livewire\Calidad\ListaMaestra;

use App\Livewire\PageWithDashboard;
use App\Models\Area;
use App\Models\ListaMaestra as ListaMaestraModel;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class ListaMaestra extends PageWithDashboard
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public bool $showExportModal = false;
    public ?string $exportDate = null;

    /** Filtros */
    public ?int $areaId = null;
    public string $search = '';
    // Para crear
    public ?int $area_id = null;

    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public bool $showCreateModal = false;
    public bool $canCreate = false;

    /** Registro en edición/eliminación */
    public ?int $editingId = null;
    public ?int $deletingId = null;

    /** Form edición */
    public string $codigo = '';
    public string $nombre = '';
    public string $revision = '';
    public ?string $fecha_autorizacion = null; 
    public ?string $deletingLabel = null;

    // ===== Descargas (switch de administración) =====
    public bool $canManageDownloads = false; // permiso: lista-maestra.download.manage
    public bool $downloadsAllowed = false;   // único switch para PDF y ZIP

    // ======== Lifecycle de filtros ========
    public function updatedAreaId(): void { $this->resetPage(); }
    public function updatedSearch(): void { $this->resetPage(); }

    public function openExportModal(): void
    {
        // Si NO tiene permiso de exportación, descarga directa con la fecha más reciente
        if (! auth()->user()?->can('lista-maestra.export')) {
            $this->quickExportLatest();
            return;
        }

        // Admin/export: abre modal con fecha por defecto
        $this->exportDate = $this->computeDefaultExportDate(); // YYYY-MM-DD
        $this->showExportModal = true;
    }

    // 2) NUEVO: descarga directa para usuarios sin modal
    public function quickExportLatest()
    {
        $this->exportDate = $this->computeDefaultExportDate();

        return redirect()->route('calidad.lista-maestra.pdf.quick', array_filter([
            'area_id' => $this->areaId,
            'q'       => $this->search !== '' ? $this->search : null,
            'date'    => $this->exportDate,
        ], fn($v) => !is_null($v) && $v !== ''));
    }

    public function mount(): void
    {
        $user = auth()->user();

        $this->canManageDownloads = $user?->can('lista-maestra.download.manage') ?? false;
        $this->downloadsAllowed = $this->readCombinedDownloadState();

        // Solo Super Admin puede crear registros manuales
        $this->canCreate = $user?->hasRole('Super Admin') ?? false;
    }

    public function openCreate(): void
    {
        abort_unless($this->canCreate, 403);

        $this->resetValidation();

        // Limpiar formulario
        $this->editingId          = null;
        $this->area_id            = $this->areaId; // si tienes un área filtrada, la sugerimos
        $this->codigo             = '';
        $this->nombre             = '';
        $this->revision           = '';
        $this->fecha_autorizacion = now()->toDateString();

        $this->showCreateModal = true;
    }

    // ===== Helper: roles objetivo (todos menos Super Admin) =====
    protected function targetRoles(bool $includeSuperAdmin = false)
    {
        return Role::query()
            ->when(!$includeSuperAdmin, fn($q) => $q->where('name', '!=', 'Super Admin'))
            ->with('permissions') 
            ->get();
    }

    protected function readCombinedDownloadState(): bool
    {
        $roles = $this->targetRoles();
        $hasPdf = $roles->contains(fn($r) => $r->hasPermissionTo('lista-maestra.download'));
        $hasZip = $roles->contains(fn($r) => $r->hasPermissionTo('lista-maestra.files.download'));
        return $hasPdf && $hasZip;
    }

    // Alterna ambos permisos en conjunto para TODOS los roles objetivo (no afecta a Super Admin)
    public function toggleCombinedDownloads(): void
    {
        abort_unless($this->canManageDownloads, 403);

        $new = ! $this->downloadsAllowed;

        // Asegura que tomas las permissions del guard correcto
        $guard = config('auth.defaults.guard', 'web');
        $permPdf = Permission::findByName('lista-maestra.download', $guard);
        $permZip = Permission::findByName('lista-maestra.files.download', $guard);

        // Carga roles objetivo con sus permisos (sin Super Admin)
        $roles = Role::query()
            ->where('name', '!=', 'Super Admin')
            ->with('permissions') // evita lazy loading
            ->get();

        foreach ($roles as $role) {
            // Obtenemos el set actual de permisos por nombre
            $current = $role->permissions->pluck('name');

            if ($new) {
                // Agregamos PDF y ZIP si no están
                $next = $current
                    ->merge([$permPdf->name, $permZip->name])
                    ->unique()
                    ->values();
            } else {
                // Quitamos PDF y ZIP si están
                $next = $current
                    ->reject(fn ($name) => in_array($name, [$permPdf->name, $permZip->name], true))
                    ->values();
            }

            // Sincronizamos de una, evitando toques internos que disparen lazy-loading
            $role->syncPermissions($next);
            // Si quieres, refresca la relación en memoria para futuras lecturas
            $role->unsetRelation('permissions');
            $role->load('permissions');
        }

        // Limpia caché de Spatie
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->downloadsAllowed = $new;

        $this->dispatch('toastifyAlert', [
            'type' => $new ? 'success' : 'warning',
            'message' => $new
                ? 'Descargas (PDF y ZIP) habilitadas para usuarios.'
                : 'Descargas (PDF y ZIP) deshabilitadas para usuarios.',
        ]);
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

    protected function rulesCreate(): array
    {
        return [
            'area_id'            => 'required|exists:areas,id',
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

    public function saveCreate(): void
    {
        abort_unless($this->canCreate, 403);

        $this->validate($this->rulesCreate());

        ListaMaestraModel::create([
            'codigo'             => $this->codigo,
            'nombre'             => $this->nombre,
            'revision'           => $this->revision,
            'fecha_autorizacion' => $this->fecha_autorizacion ?: null,
            'area_id'            => $this->area_id,
        ]);

        $this->showCreateModal = false;

        $this->dispatch('toastifyAlert', [
            'message' => 'Documento agregado a la lista maestra.',
            'type'    => 'success',
        ]);

        $this->reset(['area_id', 'codigo', 'nombre', 'revision', 'fecha_autorizacion']);
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
            ->orderBy('id')
            ->paginate(15);

        return view('livewire.calidad.lista-maestra.lista-maestra', [
            'areas' => $areas,
            'docs'  => $docs,
        ]);
    }
}
