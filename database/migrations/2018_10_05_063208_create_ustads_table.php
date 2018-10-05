<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUstadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ustads', function (Blueprint $table) {
            $table->increments('id');
            $table->String('username');
            $table->String('name');
            $table->String('email');
            $table->String('password');
            $table->String('firebaseid')->nullable();
            $table->String('logo')->nullable();
            $table->String('active');
            $table->String('phone')->nullable();
            $table->String('category')->nullable();
            $table->String('info')->nullable();
            $table->String('code')->nullable();
            $table->String('price')->nullable();
            $table->String('skills')->nullable();
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
        Schema::dropIfExists('ustads');
    }
}
