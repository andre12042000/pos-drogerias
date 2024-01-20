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
            $table->string('name')->unique();
            $table->integer('stock')->nullable();
            $table->integer('stock_min')->nullable();
            $table->integer('stock_max')->nullable();
            $table->string('image')->nullable();
            $table->double('sell_price',12,0)->nullable();
            $table->double('sell_price_tecnico',12,0)->nullable();
            $table->double('sell_price_distribuidor',12,0)->nullable();
            $table->enum('status',['ACTIVE','DESACTIVE'])->default('ACTIVE');
            $table->double('last_price',12,0)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->unsignedBigInteger('medida_id')->nullable();
            $table->foreign('medida_id')->references('id')->on('unidad_medidas')->onDelete('set null');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->double('exento',12,0)->nullable();
            $table->double('excluido',12,0)->nullable();
            $table->double('no_gravado',12,0)->nullable();
            $table->double('gravado',12,0)->nullable();
            $table->integer('contenido_interno_caja ')->nullable();
            $table->integer('contenido_interno_blister')->nullable();
            $table->integer('contenido_interno_unidad')->nullable();
            $table->integer('costo_caja ')->nullable();
            $table->integer('costo_blister')->nullable();
            $table->integer('costo_unidad')->nullable();
            $table->integer('valor_iva_caja ')->nullable();
            $table->integer('valor_iva_blister')->nullable();
            $table->integer('valor_iva_unidad')->nullable();
            $table->date('expiration')->nullable();
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
