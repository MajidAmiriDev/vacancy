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
        Schema::create('vacancy_age_price', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['adult', 'child', 'baby']);
            $table->integer('additional_amount');
            $table->integer('vacancy_id')->unsigned();
            $table->index('vacancy_id');
            $table->foreign('vacancy_id')->references('id')->on('vacancy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_age_price');
    }
};
