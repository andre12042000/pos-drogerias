<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatosProductToPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->double('discount_tax')->nullable(); //Valor descontado en cada producto
            $table->double('discount')->nullable(); //Valor descontado en cada producto
            $table->decimal('tax')->nullable(); //Iva del producto
            $table->double('mount_tax')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            //
        });
    }
}
