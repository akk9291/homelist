<?php

namespace App\Repositories\Contracts;

use App\DTOs\LoginActivityDTO;
use App\Models\LoginActivity;

interface LoginActivityRepositoryInterface
{
    /**
     * Store login activity details.
     */
    public function store(LoginActivityDTO $dto): LoginActivity;
}
