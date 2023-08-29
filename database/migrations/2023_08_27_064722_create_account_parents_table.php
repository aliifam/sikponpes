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
        Schema::create('account_parents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pesantren_id');
            $table->foreign('pesantren_id')->references('id')->on('pesantrens')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('parent_code');
            $table->string('parent_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_parents');
    }
};
