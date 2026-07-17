<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFloorPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'price',
        'size',
        'bedrooms',
        'bathrooms',
        'image',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'size' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
