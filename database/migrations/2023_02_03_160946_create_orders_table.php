<?php

use App\Models\Orders;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->string('prefijo')->nullable();
            $table->integer('nro');
            $table->string('full_nro'); //Prefijo y nro unificado


            $table->string('descripcion')->nullable();
            $table->double('valor');
            $table->double('abono');
            $table->double('saldo');

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('set null');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');  //usuario que registra la orden

            $table->unsignedBigInteger('assigned')->nullable();
            $table->foreign('assigned')->references('id')->on('users')->onDelete('set null'); //asignado al tecnico o funcionario

            $table->enum('status', [Orders::solicitado, Orders::entregado])->default(Orders::solicitado);

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
        Schema::dropIfExists('orders');
    }
}
