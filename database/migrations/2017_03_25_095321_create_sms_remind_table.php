<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsRemindTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_sms_remind_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile', 20);
            $table->dateTime('date')->nullable();
            $table->tinyInteger('type')->comment('2:登陆, 3:找回密码');
            $table->tinyInteger('status')->comment('1:有效 0:无效 2:已使用');
            $table->string('result')->default("");
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
        Schema::dropIfExists('send_sms_remind_log');
    }
}
