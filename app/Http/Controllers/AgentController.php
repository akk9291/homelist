<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;

class AgentController extends Controller
{
    public function show(int $id)
    {
        $agent = User::where('id', $id)
            ->where('role', 'agent')
            ->with('agent')
            ->firstOrFail();

        $properties = Property::where('published', true)
            ->where('agent_id', $id)
            ->with(['type', 'status', 'city', 'state'])
            ->latest('published_at')
            ->paginate(12);

        return view('agents.show', compact('agent', 'properties'));
    }
}
