<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumoInternoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumo_interno_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('consumo_interno_id');
            $table->foreign('consumo_interno_id')->references('id')->on('consumo_internos');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->string('forma');
            $table->integer('quantity');
            $table->double('price');


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
        Schema::dropIfExists('consumo_interno_detalles');
    }
}
