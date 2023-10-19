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
        Schema::table('journal_details', function (Blueprint $table) {
            $table->string('id', 255)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_journals', function (Blueprint $table) {
            //change foreign key to string
            $table->dropForeign('general_journals_journal_detail_id_foreign');
        });
    }
};
