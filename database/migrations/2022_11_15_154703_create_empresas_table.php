<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->string('nit', 20)->require();
            $table->string('dv', 1)->nullable();
            $table->string('telefono', 30)->require();
            $table->string('email', 100)->require();
            $table->string('pre_facturacion', 10)->nullable();
            $table->string('pre_orden', 10)->nullable();
            $table->string('pre_servicio', 10)->nullable();
            $table->string('pre_abono', 10)->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('empresas');
    }
}
