<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface LeadRepositoryInterface extends BaseRepositoryInterface
{
    public function getLeadsByAgent(int $agentId): Collection;
}
