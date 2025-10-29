<?php

namespace App\Livewire\Calidad\Quejasugerencia;

use App\Livewire\PageWithDashboard;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class Quejas extends PageWithDashboard
{
    public string $fecha;
    public ?string $folio = null;

    public string $nombre;
    public string $email;
    public string $numcontrol;
    public ?int $semestre = null;
    public ?string $carrera_code = null;
    public ?string $grupo = null;
    public ?string $turno = null;
    public ?string $aula = null;

    #[Validate('nullable|string|max:20')]
    public ?string $telefono = null;

    #[Validate('required|string|in:queja,sugerencia')]
    public string $tipo = 'queja';

    #[Validate('required|string|min:10|max:4000')]
    public string $descripcion = '';

    public bool $enviada = false;
    public bool $saving = false;

    // NEW: controla el modal de confirmación
    public bool $showConfirm = false;

    public function mount(): void
    {
        $alumno = Auth::guard('students')->user();

        $this->fecha        = now()->toDateString();
        $this->nombre       = (string) ($alumno->nombre ?? '');
        $this->email        = (string) ($alumno->email ?? '');
        $this->numcontrol   = (string) ($alumno->numcontrol ?? '');
        $this->semestre     = $alumno->semestre;
        $this->carrera_code = $alumno->carrera_code;
        $this->grupo        = $alumno->grupo;
        $this->turno        = $alumno->turno;
        $this->aula         = $alumno->aula;
        $this->telefono     = $alumno->telefono;
    }

    public function submit(): void
    {
        $this->validate();
        $this->saving = true;

        $alumno = Auth::guard('students')->user();

        if ($this->telefono !== $alumno->telefono) {
            $alumno->telefono = $this->telefono;
            $alumno->save();
        }

        $c = new Complaint();
        $c->student_id  = $alumno->id;
        $c->tipo        = $this->tipo;
        $c->titulo      = null;
        $c->descripcion = $this->descripcion;
        $c->estado      = 'abierta';
        $c->origen_ip   = request()->ip();
        $c->save();

        $c->refresh();
        $c->folio = 'Q-' . now()->format('Ymd') . '-' . str_pad((string) $c->id, 4, '0', STR_PAD_LEFT);
        $c->save();

        $this->folio   = $c->folio;
        $this->enviada = true;
        session()->flash('ok', "Tu solicitud {$c->folio} fue enviada.");

        $this->saving  = false;

        $this->descripcion = '';
        $this->redirectRoute('students.quejas.index', navigate: true);
    }

    // NEW: se llama desde el modal al confirmar
    public function confirmSubmit(): void
    {
        // Si la validación falla, lanzará excepción y el modal quedará abierto,
        // así el usuario corrige. Solo cerramos si todo salió bien.
        $this->submit();
        $this->showConfirm = false;
    }

    public function render()
    {
        return view('livewire.calidad.quejasugerencia.quejas');
    }
}
