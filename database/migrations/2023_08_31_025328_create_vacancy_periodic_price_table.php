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
        Schema::create('vacancy_periodic_price', function (Blueprint $table) {
            $table->id();
            $table->integer('additional_amount');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('type', ['+', '-']);
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
        Schema::dropIfExists('vacancy_periodic_price');
    }
};
