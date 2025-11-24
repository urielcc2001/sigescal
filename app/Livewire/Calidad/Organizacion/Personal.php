<?php

namespace App\Livewire\Calidad\Organizacion;

use App\Livewire\PageWithDashboard;
use App\Models\OrgAssignment;
use App\Models\OrgDepartment;
use App\Models\OrgPosition;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Personal extends PageWithDashboard
{
    use WithPagination, AuthorizesRequests;
    use LivewireAlert;

    // Filtros / UI
    public string $search = '';
    public string $nivel = '';     
    public ?int   $departmentId = null;

    public string $bloque = '';

    // Modal de reasignación
    public bool $showAssignModal = false;
    public ?int $positionId = null;

    // Formulario reasignación
    public ?int $user_id = null;
    public string $nombre = '';
    public ?string $correo = null;
    public ?string $telefono = null;
    public ?string $inicio = null;
    public string $modo = '';      // '', 'editar', 'nuevo', 'vacante'
    public ?int $vigenteId = null; // para validar que existe vigente al editar/cerrar


    protected function rules(): array
    {
        return [
            'positionId' => ['required', 'exists:org_positions,id'],
            'nombre'     => ['required', 'string', 'max:255'],
            'correo'     => ['nullable', 'email', 'max:255'],
            'telefono'   => ['nullable', 'string', 'max:50'],
            'inicio'  => 'required_if:modo,editar,nuevo|date|after_or_equal:1900-01-01',
            'user_id'    => ['nullable', 'exists:users,id'],
        ];
    }

    public function mount(): void
    {
        // Seguridad extra (además del middleware de ruta)
        $this->authorize('org.personal.view');

        // Valor por defecto para inicio
        $this->inicio = $this->inicio ?? now()->toDateString();
    }

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingNivel(): void        { $this->resetPage(); }
    public function updatingDepartmentId(): void { $this->resetPage(); }
    public function updatingBloque(): void       { $this->resetPage(); }

    private function bloqueMapa(): array
    {
        return [
            'vinculacion' => [
                'subdir'   => ['subdir-vinculacion'],
                'puestos'  => [],
                'deps'     => [
                    'planeacion-programacion-presupuesto',
                    'gestion-tecnologica-vinculacion',
                    'comunicacion-difusion',
                    'servicios-escolares',
                    'centro-informacion',
                    'actividades-extraescolares',
                ],
            ],
            'servicios' => [
                'subdir'  => ['subdir-servicios'],
                'puestos' => [],
                'deps'    => [
                    'recursos-humanos',
                    'recursos-financieros',
                    'recursos-materiales-servicios',
                    'mantenimiento-equipo',
                    'servicios-generales',
                    'centro-computo',
                ],
            ],
            'academico' => [
                'subdir'  => ['subdir-academica'],
                'puestos' => [],
                'deps'    => [
                    'ciencias-de-la-tierra',
                    'sistemas-computacion',
                    'electrica-electronica',
                    'metal-mecanica',
                    'ciencias-economico-administrativas',
                    'ciencias-basicas',
                    'quimica-bioquimica',
                    'desarrollo-academico',
                    'division-estudios-profesionales',
                ],
            ],
        ];
    }

    public function openAssign(int $positionId): void
    {
        $this->authorize('org.personal.edit');

        $this->resetValidation();
        $this->positionId = $positionId;

        $position = OrgPosition::with('vigente')->findOrFail($positionId);
        $vigente  = $position->vigente;

        $this->vigenteId = $vigente?->id;

        // Prefill con datos del vigente si lo hay
        $this->user_id  = $vigente?->user_id;
        $this->nombre   = $vigente?->nombre ?? '';
        $this->correo   = $vigente?->correo;
        $this->telefono = $vigente?->telefono;
        $this->inicio   = ($vigente?->inicio?->toDateString()) ?? now()->toDateString();

        $this->modo = ''; // obligamos a elegir
        $this->showAssignModal = true;
    }


    public function saveAssignment(): void
    {
        $this->authorize('org.personal.edit');

        if (!$this->modo) {
            $this->addError('modo', 'Selecciona una acción (editar, asignar nuevo o dejar vacante).');
            return;
        }

        $this->validate(); // valida nombre/correo/etc. según tus rules

        $position = OrgPosition::with('vigente')->findOrFail($this->positionId);
        $vigente  = $position->vigente;
        $nombre   = mb_strtoupper($this->nombre ?? '', 'UTF-8');

        if ($this->modo === 'editar') {
            if (!$vigente) {
                $this->addError('modo', 'No hay titular vigente para editar. Elige "Asignar nuevo".');
                return;
            }

            // Edita al MISMO titular (sin historial)
            $vigente->update([
                'nombre'   => $nombre,
                'correo'   => $this->correo,
                'telefono' => $this->telefono,
                'inicio'   => $this->inicio,
                // no toca fin
            ]);

        } elseif ($this->modo === 'nuevo') {
            // Cierra vigente si existe y crea nuevo
            if ($vigente) {
                $vigente->update(['fin' => $this->inicio ?: now()->toDateString()]);
            }

            OrgAssignment::create([
                'org_position_id' => $position->id,
                'user_id'   => $this->user_id,
                'nombre'    => $nombre,
                'correo'    => $this->correo,
                'telefono'  => $this->telefono,
                'inicio'    => $this->inicio ?: now()->toDateString(),
                'fin'       => null,
            ]);

        } elseif ($this->modo === 'vacante') {
            // Cierra vigente y no crea nuevo
            if (!$vigente) {
                $this->addError('modo', 'No hay titular vigente para cerrar.');
                return;
            }
            $vigente->update(['fin' => now()->toDateString()]);
        }

        $this->showAssignModal = false;

        $this->alert(
            'success',
            'Cambios guardados correctamente.',
            [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]
        );
    }


    public function getDepartmentsProperty()
    {
        $excluir = [
            'direccion',
            'subdireccion-academica',
            'subdireccion-servicios',
            'subdireccion-planeacion-vinc',
            'calidad-sgc',
        ];

        return \App\Models\OrgDepartment::query()
            ->whereNotIn('slug', $excluir)
            ->orderBy('nombre')
            ->get(['id','nombre']);
    }

    public function render()
    {
        $query = OrgPosition::query()
            ->with(['vigente', 'department'])
            ->orderBy('orden');

        // Filtro por bloque (subdirección)
        if ($this->bloque) {
            $mapa = $this->bloqueMapa()[$this->bloque] ?? ['subdir'=>[], 'puestos'=>[], 'deps'=>[]];

            $depIds = OrgDepartment::whereIn('slug', $mapa['deps'])->pluck('id');
            $slugIncluidos = array_merge($mapa['subdir'], $mapa['puestos']);

            $query->where(function ($q) use ($depIds, $slugIncluidos) {
                $q->whereIn('org_department_id', $depIds)
                  ->orWhereIn('slug', $slugIncluidos);
            });
        }

        // Filtro por nivel
        if ($this->nivel) {
            if ($this->nivel === 'calidad') {
                $calidadId = OrgDepartment::where('slug', 'calidad-sgc')->value('id');
                $query->where(function($q) use ($calidadId) {
                    $q->whereIn('nivel', ['coordinacion','control'])
                    ->where('org_department_id', $calidadId);
                });

            } else {
                $query->where('nivel', $this->nivel);
            }
        }

        // Filtro por departamento
        if ($this->departmentId) {
            $query->where('org_department_id', $this->departmentId);
        }

        if ($this->search) {
            $s  = trim($this->search);
            $op = config('database.default') === 'pgsql' ? 'ILIKE' : 'LIKE';

            $query->where(function ($q) use ($s, $op) {
                $q->where('titulo', $op, "%{$s}%")
                  ->orWhere('slug',   $op, "%{$s}%")
                  ->orWhereHas('department', fn($d) => $d->where('nombre', $op, "%{$s}%"))
                  ->orWhereHas('vigente',    fn($a) => $a->where('nombre', $op, "%{$s}%"));
            });
        }

        $positions = $query->paginate(15);

        return view('livewire.calidad.organizacion.personal', [
            'positions'   => $positions,
            'departments' => $this->departments,
        ]);
    }
}
