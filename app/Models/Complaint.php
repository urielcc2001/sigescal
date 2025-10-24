<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';

    protected $fillable = [
        'folio',
        'student_id',
        'tipo',             // 'queja' | 'sugerencia'
        'titulo',           // opcional
        'descripcion',
        'estado',           // abierta | en_revision | respondida | cerrada
        'respuesta',        // texto de respuesta
        'respondida_at',
        'visto_por_alumno_at',
        'origen_ip',
    ];

    protected $casts = [
        'respondida_at'       => 'datetime',
        'visto_por_alumno_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
