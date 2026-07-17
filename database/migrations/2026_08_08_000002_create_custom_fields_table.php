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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('custom_field_groups')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('field_type'); // text, number, textarea, select, multiselect, date, checkbox, etc.
            $table->string('placeholder')->nullable();
            $table->string('default_value')->nullable();
            $table->text('validation_rules')->nullable(); // required|email|max:255 etc.
            $table->json('options_json')->nullable(); // dropdown or radio select options
            $table->json('conditional_logic')->nullable(); // rules for visibility dependencies
            $table->integer('width')->default(100); // 25, 50, 75, 100
            
            // Flags
            $table->boolean('required')->default(false);
            $table->boolean('searchable')->default(false);
            $table->boolean('sortable')->default(false);
            $table->boolean('filterable')->default(false);
            $table->boolean('exportable')->default(true);
            $table->boolean('is_repeatable')->default(false);
            
            $table->integer('sort_order')->default(0);
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
