<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginActivity extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'platform',
        'browser',
        'login_at',
    ];

    protected $casts = [
        'login_at' => 'datetime',
    ];

    /**
     * Get the user that owns the login activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parse user agent and log a login activity using Clean Architecture Action & Repository.
     */
    public static function log(int $userId, string $ip, string $userAgent): void
    {
        $dto = \App\DTOs\LoginActivityDTO::fromRequest($userId, $ip, $userAgent);
        $action = app(\App\Actions\LogLoginActivityAction::class);
        $action->execute($dto);
    }
}
