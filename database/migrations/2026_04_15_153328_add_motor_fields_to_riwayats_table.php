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
        Schema::table('riwayats', function (Blueprint $table) {
            $table->foreignId('motor_id')->nullable()->constrained('motors')->nullOnDelete();
            $table->string('sistem_pembakaran')->nullable(); // Injeksi / Karburator
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayats', function (Blueprint $table) {
            $table->dropForeign(['motor_id']);
            $table->dropColumn(['motor_id', 'sistem_pembakaran']);
        });
    }
};
