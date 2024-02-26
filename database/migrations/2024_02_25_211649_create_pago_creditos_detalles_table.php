<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoCreditosDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_creditos_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('recibo_id');
            $table->foreign('recibo_id')->references('id')->on('pago_creditos');

            $table->unsignedBigInteger('credit_id');
            $table->foreign('credit_id')->references('id')->on('credits');

            $table->double('saldo_recibido');
            $table->double('valor_pagado');
            $table->double('saldo_restante');

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
        Schema::dropIfExists('pago_creditos_detalles');
    }
}
