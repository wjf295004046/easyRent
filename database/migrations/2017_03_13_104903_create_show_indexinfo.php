<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowIndexinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_indexinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('pic_path')->default("");
            $table->string('type', 10)->comment("slide:幻灯片 city:热点城市");
            $table->string('target');
            $table->string('desc')->default("");
            $table->string('extra')->default("");
            $table->tinyInteger('is_valid')->default(1)->comment("1: 有效 2：无效");
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
        Schema::dropIfExists('show_indexinfo');
    }
}
