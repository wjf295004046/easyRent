<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseRentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_rent_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('house_id');
            $table->integer('year');
            $table->tinyInteger('month');
            $table->string('detail');
            $table->unique(['house_id', 'year', 'month'], 'u_house_id_month');
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
        Schema::dropIfExists('house_rent_info');
    }
}
