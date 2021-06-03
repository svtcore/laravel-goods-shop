<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->increments('manager_id');
            $table->string('manager_fname', 50);
            $table->string('manager_lname', 50);
            $table->string('manager_mname', 50)->nullable();
            $table->string('manager_phone', 100)->unique();
            $table->string('password', 255);
            $table->string('email',100)->unique()->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('managers');
    }
}
