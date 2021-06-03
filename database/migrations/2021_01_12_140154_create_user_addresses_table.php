<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->increments('user_addr_id');
            $table->integer('f_order_id')->unsigned()->nullable();
            $table->string('user_str_name', 100);
            $table->string('user_house_num', 10);
            $table->string('user_ent_num', 5)->nullable();
            $table->string('user_apart_num', 5)->nullable();
            $table->string('user_code', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}
