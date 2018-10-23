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
            $table->String('firebaseid')->nullable();
            $table->String('logo')->nullable();
            $table->String('active');
                        $table->String('status')->nullable();

            $table->String('phone')->nullable();
            $table->String('birthday')->nullable();
            $table->String('address')->nullable();
            $table->String('code')->nullable();
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
