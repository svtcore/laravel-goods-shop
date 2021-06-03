<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_names', function (Blueprint $table) {
            $table->increments('product_name_id');
            $table->integer('f_product_id')->unsigned()->nullable();
            $table->string('product_name_lang_en')->nullable();
            $table->string('product_name_lang_de')->nullable();
            $table->string('product_name_lang_uk')->nullable();
            $table->string('product_name_lang_ru')->nullable();
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
        Schema::dropIfExists('product_names');
    }
}
