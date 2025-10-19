<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrgAssignment extends Model
{
    protected $fillable = [
        'org_position_id','user_id','nombre','correo','telefono','inicio','fin'
    ];

    protected $casts = [
        'inicio' => 'date',
        'fin'    => 'date',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(OrgPosition::class, 'org_position_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
