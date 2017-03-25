<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
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
            $table->integer('user_id');
            $table->integer('landlord_id');
            $table->integer('house_id');
            $table->tinyInteger('status')->comment("0:无效 1:审核中 2: 订单确认 3:订单完成 4:订单取消");
            $table->tinyInteger('comment_status')->default(0);
            $table->dateTime('startdate');
            $table->dateTime('enddate');
            $table->string("order_owner");
            $table->string("owner_phone");
            $table->string("number");
            $table->integer('sum_day');
            $table->integer('sum_people');
            $table->integer('sum_price');
            $table->string('livers');
            $table->string("reason")->nullable();
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
