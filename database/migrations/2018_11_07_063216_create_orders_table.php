<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->String('starttime')->nullable();
            $table->String('date')->nullable();
            $table->String('endtime')->nullable();
            $table->String('totaltime')->nullable();
             $table->String('totalbalance')->nullable();
              $table->String('ustadId')->nullable();
               $table->String('status')->nullable();
                $table->String('service')->nullable();
                 $table->String('studentId')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
