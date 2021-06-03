<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_descriptions', function (Blueprint $table) {
            $table->increments('product_desc_id');
            $table->integer('f_product_id')->unsigned()->nullable();
            $table->string('product_desc_lang_en')->nullable();
            $table->string('product_desc_lang_de')->nullable();
            $table->string('product_desc_lang_uk')->nullable();
            $table->string('product_desc_lang_ru')->nullable();
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
        Schema::dropIfExists('product_descriptions');
    }
}
