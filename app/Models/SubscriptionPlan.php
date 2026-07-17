<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'billing_period',
        'property_limit',
        'featured_limit',
        'image_limit',
        'video_limit',
        'trial_days',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'property_limit' => 'integer',
        'featured_limit' => 'integer',
        'image_limit' => 'integer',
        'video_limit' => 'integer',
        'trial_days' => 'integer',
        'is_active' => 'boolean',
    ];

    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }
}
