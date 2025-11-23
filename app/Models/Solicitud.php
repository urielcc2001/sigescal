<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'folio',
        'fecha',
        'documento_id',
        'area_id',
        'user_id',
        'tipo',                 // creacion | modificacion | baja
        'cambio_dice',
        'cambio_debe_decir',
        'justificacion',
        'requiere_capacitacion',
        'requiere_difusion',
        'estado',               // en_revision | aprobada | rechazada
        'responsable_slug',
        'codigo_nuevo',
        'titulo_nuevo',
        'revision_nueva',
    ];

    protected $casts = [
        'fecha' => 'date',
        'requiere_capacitacion' => 'bool',
        'requiere_difusion'     => 'bool',
    ];

    public function documento()
    {
        return $this->belongsTo(ListaMaestra::class, 'documento_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function historial()
    {
        return $this->hasMany(Historial::class, 'solicitud_id')->latest();
    }

    public function adjuntos()
    {
        return $this->hasMany(SolicitudAdjunto::class);
    }

    public function imagenesDice()
    {
        return $this->hasMany(SolicitudAdjunto::class)->where('seccion', 'cambio_dice')->orderBy('orden');
    }

    public function imagenesDebeDecir()
    {
        return $this->hasMany(SolicitudAdjunto::class)->where('seccion', 'cambio_debe_decir')->orderBy('orden');
    }

}
