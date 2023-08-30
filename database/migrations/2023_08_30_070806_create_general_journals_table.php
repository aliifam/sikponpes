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
        Schema::create('general_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_journal_detail')->constrained('journal_details')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_account')->constrained('accounts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('position', ['debit', 'credit']);
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_journals');
    }
};
