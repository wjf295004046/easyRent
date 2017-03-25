<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendSmsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_sms_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile', 20);
            $table->integer('verify');
            $table->tinyInteger('type')->comment('1:登陆, 2:找回密码');
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
        Schema::dropIfExists('send_sms_log');
    }
}
