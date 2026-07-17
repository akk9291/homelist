<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyCustomFieldValue extends Model
{
    use HasFactory;

    protected $table = 'property_custom_field_values';

    protected $fillable = [
        'property_id',
        'custom_field_id',
        'value',
    ];

    /**
     * Get the property that owns the value.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * Get the custom field definition.
     */
    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id');
    }
}
