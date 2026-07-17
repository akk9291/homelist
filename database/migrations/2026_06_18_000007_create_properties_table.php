<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wp_property_id')->nullable()->index();
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('set null');
            $table->foreignId('property_type_id')->nullable()->constrained('property_types')->onDelete('set null');
            $table->foreignId('property_status_id')->nullable()->constrained('property_statuses')->onDelete('set null');
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0.00)->index();
            
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area')->nullable(); // property size in sq ft
            
            $table->string('facing')->nullable();
            $table->string('ownership')->nullable();
            $table->string('furnishing')->nullable();
            
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->string('featured_image')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(true)->index();
            $table->timestamp('published_at')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
