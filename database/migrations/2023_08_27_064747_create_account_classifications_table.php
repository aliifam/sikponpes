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
        Schema::create('account_classifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesantren_id')->constrained('pesantrens')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('parent_id')->constrained('account_parents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('classification_code');
            $table->string('classification_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_classifications');
    }
};
