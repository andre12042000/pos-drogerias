<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Ubicacion;
use App\Models\UnidadMedida;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    public $precio_tecnico, $precio_distribuidor, $precio_compra, $laboratorio;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $this->obtenerprecios($row['8']);
        $laboratorio = $this->crearlaboratorios($row['4']);
        $presentacion = $this->crearpresentaciones($row['3']);
        $ubicacion = $this->crearubicacion($row['5']);



        return new Product([
            'code'                      => $row['1'],
            'name'                      => $row['2'],
            'stock'                     => 0,
            'stock_min'                 => 0,
            'stock_max'                 => 0,
            'image'                     => null,
            'sell_price'                => $row['27'],
            'sell_price_tecnico'        => $row['27'],
            'sell_price_distribuidor'   => $row['27'],
            'status'                    => 'ACTIVE',
            'last_price'                => $this->precio_compra,
            'category_id'               => 1,
            'medida_id'                 => 1,
            'brand_id'                  => 1,
            'laboratorio_id'            => $laboratorio,
            'presentacion_id'           => $presentacion,
            'ubicacion_id'              => $ubicacion,
            'expiration'                => Null,



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
            $table->integer('inventario_caja ')->nullable();
            $table->integer('inventario_blister')->nullable();
            $table->integer('inventario_unidad')->nullable();
            $table->integer('costo_caja ')->nullable();
            $table->integer('costo_blister')->nullable();
            $table->integer('costo_unidad')->nullable();
            $table->integer('valor_iva_caja ')->nullable();
            $table->integer('valor_iva_blister')->nullable();
            $table->integer('valor_iva_unidad')->nullable();
            $table->date('expiration')->nullable();
        ]);
    }

    public function crearlaboratorios($row){
        $laboratorio = Laboratorio::where('name', $row)->first();

    // Si no existe, crea un nuevo laboratorio
    if (!$laboratorio) {
        $laboratorio = Laboratorio::create([
            'name'   => $row,
            'status' => 'ACTIVE',
        ]);
    }
    return $laboratorio->id;
    }

    public function crearpresentaciones($row){
        $presentacion = Presentacion::where('name', $row)->first();

    // Si no existe, crea un nuevo laboratorio
    if (!$presentacion) {
        $presentacion = Presentacion::create([
            'name'   => $row,
            'status' => 'ACTIVE',
        ]);
    }

    return $presentacion->id;
    }


    public function crearubicacion($row){
        $ubicacion = Ubicacion::where('name', $row)->first();

    // Si no existe, crea un nuevo laboratorio
    if (!$ubicacion) {
        $ubicacion = Ubicacion::create([
            'name'   => $row,
            'status' => 'ACTIVE',
        ]);
    }

    return $ubicacion->id;
    }

    public function crearmedida($row){
        $unidad = UnidadMedida::where('name', $row)->first();

    // Si no existe, crea un nuevo laboratorio
    if (!$unidad) {
        $unidad = UnidadMedida::create([
            'name'   => $row,
        ]);
    }
    return $unidad->id;
    }

    public function obtenerprecios($sell_price)
    {

       $this->precio_compra = ($sell_price - (($sell_price * 80) / 100));
       $this->precio_tecnico = ($sell_price - (($sell_price * 10) / 100));
       $this->precio_distribuidor = ($sell_price - (($sell_price * 15) / 100));



    }
}





