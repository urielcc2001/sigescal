<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrgDepartment extends Model
{
    protected $fillable = ['slug','nombre','area_id'];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(OrgPosition::class, 'org_department_id');
    }
}
