<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'photo',
        'about',
        'kyc_status',
        'kyc_document_path',
        'is_verified',
        'rating',
        'rating_count',
    ];

    /**
     * Get the user who owns the Agent profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all properties owned by the agent.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'agent_id', 'user_id');
    }
}
