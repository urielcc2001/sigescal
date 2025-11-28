<?php

namespace App\Livewire\Calidad\Quejasugerencia;

use App\Livewire\PageWithDashboard;
use App\Models\Complaint;
use App\Models\Student; // 👈 modelo de alumnos
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;

class Quejas extends PageWithDashboard
{
    public string $fecha;
    public ?string $folio = null;

    #[Validate('required|string|max:255')]
    public string $nombre = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:20')]
    public string $numcontrol = '';

    #[Validate('required|integer|min:1|max:12')]
    public ?int $semestre = null;

    #[Validate('required|string|in:LAOK,LCPOK,IBQOK,ICOK,IEOK,IEMOK,IIOK,IGEOK,ISCOK,IDAOK')]
    public ?string $carrera_code = null;

    #[Validate('required|string|in:A,B,C,D,E')]
    public ?string $grupo = null;

    #[Validate('required|string|in:matutino,vespertino,sabatino')]
    public ?string $turno = null;

    #[Validate('nullable|string|max:100')]
    public ?string $aula = null;

    #[Validate('nullable|string|max:20')]
    public ?string $telefono = null;

    #[Validate('required|string|in:queja,sugerencia')]
    public string $tipo = 'queja';

    #[Validate('required|string|min:10|max:4000')]
    public string $descripcion = '';

    public bool $enviada = false;
    public bool $saving  = false;

    public bool $showConfirm = false;

    public function mount(): void
    {
        $this->fecha = now()->toDateString();
    }

    /**
     * Cuando cambia el número de control, intentamos cargar al alumno si ya existe
     */
    public function updatedNumcontrol(): void
    {
        $num = trim($this->numcontrol);

        if ($num === '') {
            // Si borra el número de control, limpiamos todo
            $this->nombre       = '';
            $this->email        = '';
            $this->semestre     = null;
            $this->carrera_code = null;
            $this->grupo        = null;
            $this->turno        = null;
            $this->aula         = null;
            $this->telefono     = null;
            return;
        }

        $student = Student::where('numcontrol', $num)->first();

        if ($student) {
            // Autocompletar datos si existe
            $this->nombre       = $student->nombre       ?? '';
            $this->email        = $student->email        ?? '';
            $this->semestre     = $student->semestre;
            $this->carrera_code = $student->carrera_code;
            $this->grupo        = $student->grupo;
            $this->turno        = $student->turno;
            $this->aula         = $student->aula;
            $this->telefono     = $student->telefono;
        } else {
            // Si NO existe alumno con ese numcontrol → limpiar datos
            $this->nombre       = '';
            $this->email        = '';
            $this->semestre     = null;
            $this->carrera_code = null;
            $this->grupo        = null;
            $this->turno        = null;
            $this->aula         = null;
            $this->telefono     = null;
        }
    }

    public function submit(): void
    {
        $this->validate();
        $this->saving = true;

        // 1) Buscar alumno por numcontrol o email
        $student = Student::where('numcontrol', $this->numcontrol)
            ->orWhere('email', $this->email)
            ->first();

        // 2) Si no existe, lo creamos (primer registro del alumno)
        if (! $student) {
            $student = new Student();
            $student->numcontrol = $this->numcontrol;
            $student->email      = $this->email;

            $student->password             = Hash::make(Str::random(12));
            $student->must_change_password = true;
            $student->status               = 'activo';
        }

        // 3) Actualizamos sus datos con lo que llenó en el formulario
        $student->nombre       = $this->nombre;
        $student->semestre     = $this->semestre;
        $student->carrera_code = $this->carrera_code;
        $student->grupo        = $this->grupo;
        $student->turno        = $this->turno;
        $student->aula         = $this->aula;
        $student->telefono     = $this->telefono;
        $student->email        = $this->email;
        $student->save();

        // 4) Creamos la queja/sugerencia ligada al student_id
        $c = new Complaint();
        $c->student_id  = $student->id;
        $c->tipo        = $this->tipo;
        $c->titulo      = null;
        $c->descripcion = $this->descripcion;
        $c->estado      = 'abierta';
        $c->origen_ip   = request()->ip();
        $c->save();

        // 5) Generamos el folio
        $c->refresh();
        $c->folio = 'Q-' . now()->format('Ymd') . '-' . str_pad((string) $c->id, 4, '0', STR_PAD_LEFT);
        $c->save();

        $this->folio   = $c->folio;
        $this->enviada = true;
        $this->saving  = false;

        $this->descripcion = '';

        session()->flash('ok', "Tu solicitud {$c->folio} fue enviada.");
    }

    public function confirmSubmit(): void
    {
        $this->submit();
        $this->showConfirm = false;
    }

    public function render()
    {
        return view('livewire.calidad.quejasugerencia.quejas');
    }
}
