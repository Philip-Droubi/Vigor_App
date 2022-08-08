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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ex_id')->constrained('challenges_exercises')->cascadeOnDelete();
            $table->string('name')->default('');
            $table->string('desc')->default('');
            $table->string('img_path')->default('Default/35mnhgfrewqw34rfvbhy65r4edfgnhgr4e3sxcwtgr4htyuChallenge.PNG');
            $table->string('is_time')->default(false);
            $table->bigInteger('total_count')->default(1);
            $table->date('end_time');
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
        Schema::dropIfExists('challenges');
    }
};
