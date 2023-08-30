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
        Schema::create('budget_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('category_id')->constrained('budget_account_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->foreignId('pesantren_id')->constrained('pesantrens')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_accounts');
    }
};
