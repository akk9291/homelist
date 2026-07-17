<?php

namespace App\Repositories\Eloquent;

use App\DTOs\LoginActivityDTO;
use App\Models\LoginActivity;
use App\Repositories\Contracts\LoginActivityRepositoryInterface;

class LoginActivityRepository implements LoginActivityRepositoryInterface
{
    /**
     * Store login activity details.
     */
    public function store(LoginActivityDTO $dto): LoginActivity
    {
        return LoginActivity::create([
            'user_id' => $dto->userId,
            'ip_address' => $dto->ipAddress,
            'user_agent' => $dto->userAgent,
            'device_type' => $dto->deviceType,
            'platform' => $dto->platform,
            'browser' => $dto->browser,
            'login_at' => now(),
        ]);
    }
}
