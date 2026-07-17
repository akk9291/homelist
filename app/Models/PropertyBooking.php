<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'booking_date',
        'time_slot',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    /**
     * Get the property being booked.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the customer (user) who booked the slot.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
