<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomFieldGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'status',
    ];

    /**
     * Get the fields in this group.
     */
    public function customFields(): HasMany
    {
        return $this->hasMany(CustomField::class, 'group_id')->orderBy('sort_order');
    }
}
