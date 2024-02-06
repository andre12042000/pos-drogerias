<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemperaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temperatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sites_temperatures_id');
            $table->foreign('sites_temperatures_id')->references('id')->on('sites_temperatures');
            $table->date('fecha');
            $table->time('hora');
            $table->decimal('temperatura', 8, 2);
            $table->decimal('humedad', 8, 2);
            $table->decimal('cadena_frio', 8, 2);
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
        Schema::dropIfExists('temperatures');
    }
}
