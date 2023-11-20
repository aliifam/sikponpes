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
        Schema::table('general_journals', function (Blueprint $table) {
            $table->foreignId('perusahaan_id')->nullable()->constrained('perusahaans')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('santri_id')->nullable()->constrained('santris')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_journals', function (Blueprint $table) {
            $table->dropForeign(['perusahaan_id']);
            $table->dropForeign(['santri_id']);
            $table->dropColumn(['perusahaan_id', 'santri_id']);
        });
    }
};
