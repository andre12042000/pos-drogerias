<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToImpresoras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('impresoras', function (Blueprint $table) {
            $table->string('nombre')->unique();
            $table->boolean('predeterminada')->default(True);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('impresoras', function (Blueprint $table) {
            //
        });
    }
}
