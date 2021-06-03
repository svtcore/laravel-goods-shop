<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('order_id');
            $table->integer('f_user_id')->unsigned()->nullable();
            $table->integer('f_pay_t_id')->unsigned()->nullable();
            $table->integer('f_manager_id')->unsigned()->nullable();
            $table->float('order_full_price', 10)->unsigned()->nullable();
            $table->text('order_note')->nullable();
            $table->string('order_code')->nullable();
            $table->string('order_fname', 100)->nullable();
            $table->string('order_lname', 100)->nullable();
            $table->string('order_phone', 100);
            $table->enum('order_status', ['created','processing', 'completed', 'canceled']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('f_user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('f_manager_id')->references('manager_id')->on('managers')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('f_pay_t_id')->references('pay_t_id')->on('payment_types')->onDelete('set null')->onUpdate('cascade');
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
