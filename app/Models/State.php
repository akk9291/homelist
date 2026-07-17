<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'state_id');
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }
}
