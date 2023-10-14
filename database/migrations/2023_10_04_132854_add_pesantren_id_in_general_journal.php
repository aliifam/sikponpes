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
            $table->foreignId('pesantren_id')->after('id')->constrained('pesantrens')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_journals', function (Blueprint $table) {
            $table->dropForeign(['pesantren_id']);
            $table->dropColumn('pesantren_id');
        });
    }
};
