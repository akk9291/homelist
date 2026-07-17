<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'amount',
        'gst_amount',
        'total_amount',
        'payment_gateway',
        'payment_status',
        'transaction_id',
        'invoice_number',
        'utr_number',
        'notes',
        'manual_approved_by',
        'manual_approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'manual_approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function manualApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manual_approved_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TransactionLog::class);
    }
}
