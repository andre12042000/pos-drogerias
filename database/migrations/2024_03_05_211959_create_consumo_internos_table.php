<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumoInternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumo_internos', function (Blueprint $table) {
            $table->id();

            $table->string('prefijo')->nullable();
            $table->integer('nro');
            $table->string('full_nro'); //Prefijo y nro unificado

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->double('total',15,0);

            $table->string('status', 20);

            $table->double('valor_anulado', 15, 0)->default(0);

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
        Schema::dropIfExists('consumo_internos');
    }
}
