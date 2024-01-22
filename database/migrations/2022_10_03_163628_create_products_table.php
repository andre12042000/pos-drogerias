<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('name');

            $table->enum('status',['ACTIVE','DESACTIVE'])->default('ACTIVE');

            $table->float('iva_product',15,0)->nullable();
            $table->double('valor_iva_caja',15,0)->nullable();
            $table->double('valor_iva_blister',15,0)->nullable();
            $table->double('valor_iva_unidad',15,0)->nullable();

            $table->integer('stock')->nullable();
            $table->integer('stock_min')->nullable();
            $table->integer('stock_max')->nullable();
            $table->string('image')->nullable();

            $table->boolean('disponible_caja')->default(false);
            $table->boolean('disponible_blister')->default(false);
            $table->boolean('disponible_unidad')->default(false);

            $table->string('contenido_interno_caja')->nullable();
            $table->string('contenido_interno_blister')->nullable();
            $table->string('contenido_interno_unidad')->nullable();

            $table->double('costo_caja',15,0)->nullable();
            $table->double('costo_blister',15,0)->nullable();
            $table->double('costo_unidad',15,0)->nullable();


            $table->double('precio_caja',15,0)->nullable();
            $table->double('precio_blister',15,0)->nullable();
            $table->double('precio_unidad',15,0)->nullable();

            $table->double('precio_compra',15,0)->nullable();









            $table->unsignedBigInteger('medida_id')->nullable();
            $table->foreign('medida_id')->references('id')->on('unidad_medidas')->onDelete('set null');






            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');

            $table->string('exento')->nullable();
            $table->string('excluido')->nullable();
            $table->string('no_gravado')->nullable();
            $table->string('gravado')->nullable();


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
        Schema::dropIfExists('products');
    }
}
