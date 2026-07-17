<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'commission_rate',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'commission_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
