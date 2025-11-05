<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmFolder extends Model
{
    protected $fillable = ['name','parent_id','slug_path'];

    public function parent()   { return $this->belongsTo(self::class, 'parent_id'); }
    public function children() { return $this->hasMany(self::class, 'parent_id'); }
    public function files()    { return $this->hasMany(LmFile::class, 'folder_id'); }

    public static function ensurePath(string $slugPath): self {
        $parts = array_values(array_filter(explode('/', $slugPath)));
        $parentId = null; $currPath = '';
        foreach ($parts as $p) {
            $currPath = ltrim($currPath . '/' . trim($p), '/');
            $folder = self::firstOrCreate(
                ['slug_path' => $currPath],
                ['name' => $p, 'parent_id' => $parentId]
            );
            $parentId = $folder->id;
        }
        return self::where('slug_path', $slugPath)->first();
    }
}