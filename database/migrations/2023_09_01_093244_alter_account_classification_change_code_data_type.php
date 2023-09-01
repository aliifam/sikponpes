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
        Schema::table('account_classifications', function (Blueprint $table) {
            $table->string('classification_code')->change();
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->string('account_code')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_classifications', function (Blueprint $table) {
            $table->integer('classification_code')->change();
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->integer('account_code')->change();
        });
    }
};
