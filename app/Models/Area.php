<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    /** Documentos de la lista maestra asignados a esta área */
    public function documentos(): HasMany
    {
        return $this->hasMany(ListaMaestra::class, 'area_id');
    }

    /** Solicitudes (creación/actualización) asociadas a esta área */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'area_id');
    }

    /** Usuarios asignados a esta área (relación muchos-a-muchos) */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
