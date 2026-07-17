<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmFollowup extends Model
{
    use HasFactory;

    protected $table = 'crm_followups';

    protected $fillable = [
        'lead_id',
        'type',
        'followup_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'followup_date' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }
}
