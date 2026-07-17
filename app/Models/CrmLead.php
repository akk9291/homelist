<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'name',
        'email',
        'phone',
        'source',
        'status',
        'property_id',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function followups(): HasMany
    {
        return $this->hasMany(CrmFollowup::class, 'lead_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CrmNote::class, 'lead_id');
    }
}
