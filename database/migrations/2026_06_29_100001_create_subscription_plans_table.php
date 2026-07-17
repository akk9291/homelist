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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->string('billing_period')->default('monthly'); // monthly, quarterly, yearly, lifetime
            $table->integer('property_limit')->nullable(); // null means unlimited
            $table->integer('featured_limit')->nullable();
            $table->integer('image_limit')->nullable();
            $table->integer('video_limit')->nullable();
            $table->integer('trial_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('ends_at')->nullable(); // null means lifetime or active till manual expiry
            $table->string('status')->default('active'); // active, expired, cancelled, pending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
