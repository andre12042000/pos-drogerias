<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastosDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gastos_id');
            $table->foreign('gastos_id')->references('id')->on('gastos');
            $table->integer('cantidad');
            $table->string('descripcion');
            $table->double('precio');


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
        Schema::dropIfExists('gastos_detalles');
    }
}
