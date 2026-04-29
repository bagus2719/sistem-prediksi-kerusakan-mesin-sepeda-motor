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
        Schema::create('c45_models', function (Blueprint $table) {
            $table->id();
            $table->json('tree_data')->nullable(); // The generated decision tree rules
            $table->float('accuracy')->nullable(); // Model accuracy percentage
            $table->boolean('is_active')->default(false); // Only one active model at a time
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c45_models');
    }
};
