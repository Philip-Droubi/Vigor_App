<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_excersises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('excersise_id')->constrained('excersises');
            $table->foreignId('workout_id')->constrained('workouts');
            $table->integer('count')->nullable();
            $table->integer('length')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workout_excersises');
    }
};
