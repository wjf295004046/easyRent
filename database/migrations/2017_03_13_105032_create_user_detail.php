<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unique();
            $table->string('real_name', 20)->default("");
            $table->string('id_card')->default("");
            $table->string('passport')->default("");
            $table->string('pic_path')->default("/common/mrtx.jpg");
            $table->string('sex')->default("");
            $table->dateTime('birth')->nullable();
            $table->string('country')->default('中国');
            $table->string('province')->default("");
            $table->string('city')->default("");
            $table->string('education')->default("");
            $table->string('job')->default("");
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
        Schema::dropIfExists('user_detail');
    }
}
