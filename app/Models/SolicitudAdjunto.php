<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudAdjunto extends Model
{
    protected $table = 'solicitud_adjuntos';

    protected $fillable = [
        'solicitud_id','seccion','path','disk','original_name','mime','size','width','height','orden'
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
}
