<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->String('username');
            $table->String('name');
            $table->String('email');
            $table->String('password');
            $table->String('firebaseid');
            $table->String('logo');
            $table->String('active');
            $table->String('phone');
            $table->String('birthday');
            $table->String('address');
            $table->String('code');
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
        Schema::dropIfExists('students');
    }
}
