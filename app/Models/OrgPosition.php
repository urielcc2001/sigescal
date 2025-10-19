<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrgPosition extends Model
{
    protected $fillable = ['slug','titulo','nivel','area_id','org_department_id','orden'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(OrgDepartment::class, 'org_department_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(OrgAssignment::class);
    }

    // Titular vigente (fin = NULL)
    public function vigente(): HasOne
    {
        return $this->hasOne(OrgAssignment::class)->whereNull('fin');
    }
}
