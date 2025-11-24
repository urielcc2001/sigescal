<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Student;
use App\Models\Solicitud;
use App\Models\ListaMaestra;
use App\Models\LmFile;
use App\Models\LmFolder;
use App\Models\Complaint;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class Dashboard extends PageWithDashboard
{
    /** ¿Es usuario con rol “admin”? (Super Admin, Calidad, Jefe, etc.) */
    public bool $isAdmin = false;

    /** ¿Es alumno (guard students)? */
    public bool $isStudent = false;

    /** Nombre a mostrar en el dashboard */
    public string $currentUserName = '';

    // --- Métricas generales ---
    public array $generalStats = [];

    // --- Solicitudes ---
    public array $solicitudesStats = [];
    public array $solicitudesChart = [];

    // --- Lista maestra ---
    public array $listaMaestraStats = [];
    public array $rolesStats = [];

    public function mount(): void
    {
        // 1) Si viene por guard "students", es alumno
        if (auth('students')->check()) {
            $this->isStudent = true;

            /** @var \App\Models\Student|null $student */
            $student = auth('students')->user();
            $this->currentUserName = $student?->nombre ?? 'Alumno';

            // Para alumnos solo mostramos bienvenida, no stats de admin
            return;
        }

        // 2) Si no es alumno, usamos el guard web normal (users)
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        $this->currentUserName = $user?->name ?? 'Usuario';

        // Ajusta los nombres de roles según tu Spatie
        $this->isAdmin = $user?->hasAnyRole([
            'Super Admin',
            'Jefe de Departamento',
            'coordinación de calidad',
        ]) ?? false;

        if ($this->isAdmin) {
            $this->loadAdminData();
        }
    }

    /**
     * Carga todos los datos necesarios para el dashboard de administración.
     */
    protected function loadAdminData(): void
    {
        // 1) Estadísticas de solicitudes (por estado)
        $this->loadSolicitudesStats();

        // 2) Estadísticas de lista maestra
        $this->loadListaMaestraStats();

        // 3) Roles y usuarios (dinámico desde la BD)

        // Todos los roles del guard "web" con conteo de usuarios
        $roles = Role::withCount('users')
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        // Usuarios SIN rol asignado
        $usersWithoutRole = User::doesntHave('roles')->count();

        // Convertimos a arreglo para la vista
        $this->rolesStats = $roles->map(function (Role $role) {
            return [
                'name'        => $role->name,
                'users_count' => $role->users_count,
            ];
        })->toArray();

        // Agregamos un rol "virtual"
        $this->rolesStats[] = [
            'name'        => 'Sin rol asignado',
            'users_count' => $usersWithoutRole,
        ];

        // Total staff = usuarios únicos CON o SIN rol (guard web)
        $totalStaffUsers = User::count(); // <-- SIMPLE Y PERFECTO

        $this->generalStats = [
            'total_staff'    => $totalStaffUsers, // número grande: TODOS los usuarios
            'total_usuarios' => User::count(),
            'total_alumnos'  => Student::count(),
            'total_quejas'   => Complaint::count(),
        ];
    }

    /**
     * Conteos de solicitudes por estado + datos para la gráfica.
     */
    protected function loadSolicitudesStats(): void
    {
        // estado: ['en_revision', 'aprobada', 'rechazada']
        $query = Solicitud::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $total = $query->sum();

        $this->solicitudesStats = [
            'total'       => $total,
            'en_revision' => $query['en_revision'] ?? 0,
            'aprobadas'   => $query['aprobada'] ?? 0,
            'rechazadas'  => $query['rechazada'] ?? 0,
        ];

        $labels = [];
        $data   = [];

        foreach ($query as $estado => $value) {
            $labels[] = $this->humanEstado($estado);
            $data[]   = $value;
        }

        $this->solicitudesChart = [
            'labels' => $labels,
            'data'   => $data,
        ];
    }

    /**
     * Conteos de documentos, archivos y carpetas de lista maestra.
     */
    protected function loadListaMaestraStats(): void
    {
        // Total de documentos en lista_maestra
        $totalDocumentos = ListaMaestra::count();

        // Documentos agrupados por área
        $porArea = ListaMaestra::query()
            ->join('areas', 'lista_maestra.area_id', '=', 'areas.id')
            ->select(
                'areas.id',
                'areas.nombre',
                'areas.codigo',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('areas.id', 'areas.nombre', 'areas.codigo')
            ->orderBy('areas.nombre')
            ->get();

        $this->listaMaestraStats = [
            'total_documentos' => $totalDocumentos,
            // array de: ['nombre' => ..., 'codigo' => ..., 'total' => ...]
            'por_area' => $porArea->map(function ($row) {
                return [
                    'nombre' => $row->nombre,
                    'codigo' => $row->codigo,
                    'total'  => $row->total,
                ];
            })->toArray(),
        ];
    }

    /**
     * Traducción “bonita” del estado de la solicitud.
     */
    protected function humanEstado(string $estado): string
    {
        return match ($estado) {
            'en_revision' => 'En revisión',
            'aprobada'    => 'Aprobadas',
            'rechazada'   => 'Rechazadas',
            default       => ucfirst(str_replace('_', ' ', $estado)),
        };
    }

    public function render(): View
    {
        return view('livewire.dashboard');
    }
}
