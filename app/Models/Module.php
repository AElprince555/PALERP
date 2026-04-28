<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $guarded =['id'];
    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'settings' => AsArrayObject::class,
            'metadata' => AsArrayObject::class,
            'is_active' => 'boolean',
        ];
    }
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany|Module
    {
        return $this->hasMany(Module::class, 'parent_id');
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id')->where('is_active', true);
    }
}
