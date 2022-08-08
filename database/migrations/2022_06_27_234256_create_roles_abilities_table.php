<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles_abilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->default(1)->constrained('roles')->cascadeOnDelete();
            $table->foreignId('ability_id')->default(1)->constrained('abilities')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles_abilities');
    }
};
