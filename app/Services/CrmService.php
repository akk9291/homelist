<?php

namespace App\Services;

use App\Models\CrmLead;
use App\Models\CrmFollowup;
use App\Models\CrmNote;
use Illuminate\Support\Facades\Log;

class CrmService extends BaseService
{
    /**
     * Create a new lead.
     */
    public function createLead(array $data): CrmLead
    {
        $lead = CrmLead::create([
            'agent_id' => $data['agent_id'],
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'source' => $data['source'] ?? 'website',
            'status' => $data['status'] ?? 'new',
            'property_id' => $data['property_id'] ?? null,
        ]);

        Log::info("CRM: New Lead created with ID: {$lead->id} for Agent ID: {$lead->agent_id}");

        // Trigger automatic in-app and email follow-up alerts if necessary
        return $lead;
    }

    /**
     * Update CRM Lead pipeline status.
     */
    public function updateLeadStatus(int $leadId, string $status): bool
    {
        $lead = CrmLead::find($leadId);
        if (!$lead) {
            return false;
        }

        $oldStatus = $lead->status;
        $lead->update(['status' => $status]);

        Log::info("CRM: Lead ID {$leadId} updated status from {$oldStatus} to {$status}");

        // Add automatic system note
        $this->addNote($leadId, 'general_note', "Status updated from {$oldStatus} to {$status}.");

        return true;
    }

    /**
     * Schedule a new lead follow-up.
     */
    public function addFollowup(int $leadId, string $type, string $date, string $notes): CrmFollowup
    {
        $followup = CrmFollowup::create([
            'lead_id' => $leadId,
            'type' => $type,
            'followup_date' => $date,
            'notes' => $notes,
            'status' => 'scheduled',
        ]);

        Log::info("CRM: Scheduled {$type} follow-up for Lead ID: {$leadId} on {$date}");

        return $followup;
    }

    /**
     * Add lead notes (call log, whatsapp conversation, etc.)
     */
    public function addNote(int $leadId, string $noteType, string $content): CrmNote
    {
        $note = CrmNote::create([
            'lead_id' => $leadId,
            'note_type' => $noteType,
            'content' => $content,
        ]);

        Log::info("CRM: Added {$noteType} to Lead ID: {$leadId}");

        return $note;
    }

    /**
     * Retrieve pipeline statistics for an agent.
     */
    public function getPipelineStats(int $agentId): array
    {
        $stats = CrmLead::where('agent_id', $agentId)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Default stages
        $stages = ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'];
        $pipeline = [];

        foreach ($stages as $stage) {
            $pipeline[$stage] = $stats[$stage] ?? 0;
        }

        return $pipeline;
    }
}
