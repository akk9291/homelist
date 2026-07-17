<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgentApiResource;
use App\Models\Agent;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AgentApiController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return AgentApiResource::collection(Agent::with('user')->get());
    }
}
