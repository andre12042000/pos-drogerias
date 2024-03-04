<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('descripcion');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('category_gastos_id');
            $table->foreign('category_gastos_id')->references('id')->on('category_gastos');
            $table->unsignedBigInteger('metodo_pago_id');
            $table->foreign('metodo_pago_id')->references('id')->on('metodo_pagos');
            $table->string('picture')->nullable();
            $table->enum('status',['APLICADO','PENDIENTE'])->default('PENDIENTE');

            $table->double('total');

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
        Schema::dropIfExists('gastos');
    }
}
