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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->decimal('gst_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_gateway'); // razorpay, phonepe, bank_transfer, offline
            $table->string('payment_status')->default('pending'); // pending, completed, failed, refunded
            $table->string('transaction_id')->nullable()->unique();
            $table->string('invoice_number')->nullable()->unique();
            $table->string('utr_number')->nullable()->unique(); // for bank/offline verification
            $table->text('notes')->nullable();
            $table->foreignId('manual_approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('manual_approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->string('gateway');
            $table->string('event');
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url')->index();
            $table->string('to_url');
            $table->integer('http_code')->default(301); // 301, 302
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('transaction_logs');
        Schema::dropIfExists('payments');
    }
};
