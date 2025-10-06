<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaMaestra extends Model
{
    protected $table = 'lista_maestra';
    protected $fillable = ['codigo','nombre','revision','fecha_autorizacion','area_id'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'documento_id');
    }
}
