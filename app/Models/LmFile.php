<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmFile extends Model
{
    protected $fillable = ['folder_id','filename','disk_path','mime','size_bytes'];
    public function folder() { return $this->belongsTo(LmFolder::class, 'folder_id'); }
}
