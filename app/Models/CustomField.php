<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'name',
        'slug',
        'field_type',
        'placeholder',
        'default_value',
        'validation_rules',
        'options_json',
        'conditional_logic',
        'width',
        'required',
        'searchable',
        'sortable',
        'filterable',
        'exportable',
        'is_repeatable',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'options_json' => 'array',
        'conditional_logic' => 'array',
        'required' => 'boolean',
        'searchable' => 'boolean',
        'sortable' => 'boolean',
        'filterable' => 'boolean',
        'exportable' => 'boolean',
        'is_repeatable' => 'boolean',
        'sort_order' => 'integer',
        'width' => 'integer',
    ];

    /**
     * Get the group that owns the custom field.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(CustomFieldGroup::class, 'group_id');
    }

    /**
     * Get the assignments for the custom field.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(CustomFieldAssignment::class, 'custom_field_id');
    }

    /**
     * Get values saved for this custom field across properties.
     */
    public function values(): HasMany
    {
        return $this->hasMany(PropertyCustomFieldValue::class, 'custom_field_id');
    }
}
