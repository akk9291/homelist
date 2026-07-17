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
        // Alter agents table to add KYC and verification fields
        Schema::table('agents', function (Blueprint $table) {
            $table->string('kyc_status')->default('pending'); // pending, verified, rejected
            $table->text('kyc_document_path')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('rating_count')->default(0);
        });

        Schema::create('agent_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->decimal('commission_rate', 5, 2)->default(0.00); // commission percentage
            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // deposit, withdraw, commission, referral
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->string('status')->default('completed'); // pending, completed, failed
            $table->timestamps();
        });

        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->text('bank_details');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('rejected_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('agent_wallets');

        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['kyc_status', 'kyc_document_path', 'is_verified', 'rating', 'rating_count']);
        });
    }
};
