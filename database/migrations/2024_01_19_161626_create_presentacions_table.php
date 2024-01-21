<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentacions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('disponible_caja')->default(false);
            $table->boolean('disponible_blister')->default(false);
            $table->boolean('disponible_unidad')->default(false);
            $table->string('por_caja')->nullable();
            $table->string('por_blister')->nullable();
            $table->string('por_unidad')->nullable();
            $table->enum('status',['ACTIVE','DESACTIVE'])->default('ACTIVE');

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
        Schema::dropIfExists('presentacions');
    }
}
