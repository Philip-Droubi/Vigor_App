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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('length')->default(0);
            $table->integer('excersise_count')->default(0);
            $table->integer('predicted_burnt_calories')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('review_count')->default(0);
            $table->string('equipment');
            $table->integer('difficulty');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('categorie_id')->references('id')->on('workout_categories')->cascadeOnDelete();
            $table->text('workout_image_url')->default('public/images/workouts/Default/default.jpg');
            $table->boolean('approval')->default(0);
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('workouts');
        Schema::enableForeignKeyConstraints();
    }
};
