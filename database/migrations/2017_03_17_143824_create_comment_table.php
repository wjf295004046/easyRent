<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('house_id');
            $table->integer('order_id');
            $table->integer('landlord_id');
            $table->tinyInteger('comment_type')->comment("1:好评2:中评3:差评");
            $table->string('comment')->nullable();
            $table->string('reply')->nullable();
            $table->tinyInteger('user_status')->comment('房客')->default(0);
            $table->tinyInteger('landlord_status')->comment("房东")->default(0);
            $table->dateTime('comment_time')->nullable();
            $table->dateTime('reply_time')->nullable();
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
        Schema::dropIfExists('comments');
    }
}
