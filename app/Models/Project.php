<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $table = "projects";

    protected $fillable = ['name', 'status'];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function timeSheet(): HasMany
    {
        return $this->hasMany(TimeSheet::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'entity_id');
    }
}
