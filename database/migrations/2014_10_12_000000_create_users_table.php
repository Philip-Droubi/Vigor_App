<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('f_name');
            $table->string('l_name')->default('');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->longtext('prof_img_url')->default('Default/RrmDmqreoLbR6dhjSVuFenDAii8uBWdqhi2fYSjK9pRISPykLSdefaultprofileimg.jpg');
            $table->string('gender')->default('male');
            $table->date('birth_date')->nullable();
            $table->text('bio')->default('');
            $table->text('country')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            //foreignKeys
            $table->foreignId('role_id')->default(1)->constrained('roles')->cascadeOnDelete();
            //
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();
    }
};
