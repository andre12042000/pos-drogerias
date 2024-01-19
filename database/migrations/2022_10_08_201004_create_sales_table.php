<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sale;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->string('prefijo')->nullable();
            $table->integer('nro');
            $table->string('full_nro'); //Prefijo y nro unificado

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');


            $table->dateTime('sale_date');

            $table->decimal('discount');
            $table->decimal('tax'); //Total de iva de toda la compra
            $table->decimal('total',15,0);

            $table->enum('type_sale', [Sale::debito, Sale::credito])->default(Sale::debito);

            $table->enum('payment_method', [Sale::efectivo, Sale::tarjeta, Sale::transferencia, Sale::cheque, Sale::deposito])->default(Sale::efectivo);


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
        Schema::dropIfExists('sales');
    }
}
