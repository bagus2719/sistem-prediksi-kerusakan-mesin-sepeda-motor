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
        Schema::create('riwayats', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('kerusakan_id')->constrained()->cascadeOnDelete();
        $table->foreignId('motor_id')->nullable()->constrained()->nullOnDelete();
        $table->string('motor_name')->nullable();
        $table->string('sistem_pembakaran')->nullable();
        $table->text('gejala_dipilih'); // JSON
        $table->float('confidence')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayats');
    }
};
