<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    public function documentos()
    {
        return $this->hasMany(ListaMaestra::class, 'area_id');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'area_id');
    }
}
