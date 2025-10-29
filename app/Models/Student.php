<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Student extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'students';       // por claridad
    // Esto NO cambia el guard automáticamente, pero es útil como referencia:
    protected string $guard_name = 'students';

    protected $fillable = [
        'numcontrol',
        'nombre',
        'semestre',
        'carrera_code',
        'grupo',
        'turno',
        'aula',
        'telefono',
        'email',
        'password',
        'must_change_password',
        'last_login_at',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',  // solo si decides usar “Recordarme”
    ];

    protected $casts = [
        'semestre'              => 'integer',
        'must_change_password'  => 'boolean',
        'last_login_at'         => 'datetime',
    ];

    /** Hash automático si asignas un password “plano” */
    public function setPasswordAttribute($value): void
    {
        if ($value && !str_starts_with($value, '$2y$') && !str_starts_with($value, '$argon2')) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /** Normaliza email a minúsculas */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = $value ? mb_strtolower(trim($value)) : null;
    }

        /** Compatibilidad: $student->initials() */
    public function initials(): string
    {
        return $this->computeInitials($this->nombre ?? $this->email ?? '');
    }

    /** Compatibilidad: $student->initials (como atributo) */
    public function getInitialsAttribute(): string
    {
        return $this->initials();
    }

    /** Utilidad interna */
    protected function computeInitials(string $fullName): string
    {
        $fullName = trim($fullName);
        if ($fullName === '') return 'AL'; // “Alumno” fallback

        // Toma primeras letras de las primeras 2–3 palabras
        $parts = preg_split('/\s+/', mb_strtoupper($fullName, 'UTF-8'));
        $letters = [];
        foreach ($parts as $p) {
            if ($p === '') continue;
            $letters[] = mb_substr($p, 0, 1, 'UTF-8');
            if (count($letters) === 2) break; // usa 2 iniciales; cambia a 3 si prefieres
        }
        return implode('', $letters) ?: 'AL';
    }
}
