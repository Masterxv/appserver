<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->String('text')->nullable();
            $table->String('fromUserId')->nullable();
            $table->String('toUserId')->nullable();
            $table->String('title')->nullable();
            $table->String('postId')->nullable();
            $table->String('userType')->nullable();
            $table->String('time')->nullable();

            $table->String('type')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
