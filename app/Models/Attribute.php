<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Attribute extends Model
{
    protected $table = "attributes";

    protected $fillable = ['name', 'type', 'options', 'slug'];

    // Observer for auto generating slug based on name
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($attribute) {
            // Generate slug only if the name is changed or slug is empty
            if (!$attribute->slug || $attribute->isDirty('name')) {
                $attribute->slug = Str::slug($attribute->name, '_');
            }
        });
    }

    public function attributeValue(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function getOptionsAttribute($value)
    {
        return $value !== null && is_string($value) ? unserialize($value) ?? null : null;
    }
}
