<?php

namespace App\Repositories\Eloquent;

use App\Models\CrmLead;
use App\Repositories\LeadRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class LeadRepository extends BaseRepository implements LeadRepositoryInterface
{
    public function __construct(CrmLead $model)
    {
        parent::__construct($model);
    }

    /**
     * Retrieve leads by Agent with Cache.
     */
    public function getLeadsByAgent(int $agentId): Collection
    {
        return Cache::remember("leads.agent.{$agentId}", 600, function () use ($agentId) {
            return $this->model
                ->where('agent_id', $agentId)
                ->with(['property', 'followups'])
                ->latest()
                ->get();
        });
    }
}
