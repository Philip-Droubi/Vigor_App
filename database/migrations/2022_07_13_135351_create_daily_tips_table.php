<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_tips', function (Blueprint $table) {
            $table->id();
            $table->string('tip')->default('');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_tips');
    }
};
