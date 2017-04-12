<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('landlord_id');
            $table->integer('address_id');
            $table->integer('comment_num')->default(0);
            $table->integer('ordered_num')->default(0);
            $table->integer('price');
            $table->string('pic_path')->default("");
            $table->tinyInteger('max_people');
            $table->tinyInteger('status')->comment('1:上架, 2:未上架, 0: 未审核, -1:审核不通过')->default(0);
            $table->integer('sum');
            $table->string('reason')->default("");
            $table->integer('deposit');
            $table->string('city');
            $table->integer('house_type');
            $table->string('house_type_detail');
            $table->integer('house_area');
            $table->tinyInteger('rent_type')->comment("1:整套出租 2:单间出租 3:床位出租 4:沙发出租");
            $table->string('bed_type')->comment("1:单人床2:双人床3:沙发4:双层床5:榻榻米6:其他");
            $table->tinyInteger('change_bed')->comment("1:每日一换2:每客一换");
            $table->string('supporting_facilities');
            $table->text('desc')->nullable();
            $table->text('internal_situation')->nullable();
            $table->text('traffic_condition')->nullable();
            $table->text('peripheral_condition')->nullable();
            $table->integer('cook_fee');
            $table->integer('clean_fee');
            $table->string('other_fee');
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
        Schema::dropIfExists('houses');
    }
}
