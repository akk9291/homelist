<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade'); // agent is user
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->default('website'); // website, manual, whatsapp, portal
            $table->string('status')->default('new'); // new, contacted, qualified, proposal, won, lost
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('crm_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('crm_leads')->onDelete('cascade');
            $table->string('type')->default('call'); // call, email, whatsapp, meeting
            $table->dateTime('followup_date');
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, completed, missed
            $table->timestamps();
        });

        Schema::create('crm_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('crm_leads')->onDelete('cascade');
            $table->string('note_type')->default('general_note'); // call_note, whatsapp_note, email_note, general_note
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_notes');
        Schema::dropIfExists('crm_followups');
        Schema::dropIfExists('crm_leads');
    }
};
