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
            //change id to string
            $table->string('id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_journals', function (Blueprint $table) {
            //change id to string
            $table->bigIncrements('id')->change();
        });
    }
};
