<?php

namespace App\Actions;

use App\DTOs\LoginActivityDTO;
use App\Models\LoginActivity;
use App\Repositories\Contracts\LoginActivityRepositoryInterface;

class LogLoginActivityAction
{
    public function __construct(
        protected LoginActivityRepositoryInterface $repository
    ) {}

    /**
     * Log user login event.
     */
    public function execute(LoginActivityDTO $dto): LoginActivity
    {
        return $this->repository->store($dto);
    }
}
