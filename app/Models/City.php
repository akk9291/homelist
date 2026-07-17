<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'slug',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'city_id');
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }
}
