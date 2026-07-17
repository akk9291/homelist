<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'file_path',
        'status',
        'rejection_reason',
    ];

    /**
     * Get the user that owns the KYC document.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
