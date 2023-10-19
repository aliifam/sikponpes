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
            //change to string and set as foreign key
            $table->string('journal_detail_id', 255)->nullable(false)->change();
            $table->foreign('journal_detail_id')->references('id')->on('journal_details')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_journal', function (Blueprint $table) {
            //change foreign key to string
            $table->dropForeign('general_journals_journal_detail_id_foreign');
        });
    }
};
