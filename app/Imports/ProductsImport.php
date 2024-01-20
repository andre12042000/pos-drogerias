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



        if($row['4'] == ''){
            $laboratorio = 1;

        }else{
            $laboratorio = $this->crearlaboratorios($row['4']);
        }

        if($row['5'] == ''){
            $ubicacion = 1;
        }else{
            $ubicacion = $this->crearubicacion($row['5']);
        }

        if($row['3'] == ''){
            $presentacion =  1;
        }else{
            $presentacion = $this->crearpresentaciones($row['3']);
        }





        return new Product([
            'code'                      => $row['1'],
            'name'                      => $row['2'],
            'stock'                     => 0,
            'stock_min'                 => 0,
            'stock_max'                 => 0,
            'image'                     => null,
            'sell_price'                => null,
            'sell_price_tecnico'        => null,
            'sell_price_distribuidor'   => null,
            'status'                    => 'ACTIVE',
            'last_price'                => $row['18'],
            'category_id'               => 1,
            'medida_id'                 => 1,
            'brand_id'                  => 1,
            'laboratorio_id'            => $laboratorio,
            'presentacion_id'           => $presentacion,
            'ubicacion_id'              => $ubicacion,
            'expiration'                => Null,
            'exento'                    => $row['6'],
            'excluido'                  => $row['7'],
            'no_gravado'                => $row['8'],
            'gravado'                   => $row['9'],
            'contenido_interno_caja'    => $row['10'],
            'contenido_interno_blister' => $row['11'],
            'contenido_interno_unidad'  => $row['12'],
            'inventario_caja'           => $row['14'],
            'inventario_blister'        => $row['15'],
            'inventario_unidad'         => $row['16'],
            'costo_caja'                => $row['18'],
            'costo_blister'             => $row['19'],
            'costo_unidad'              => $row['20'],
            'valor_iva_caja'            => $row['21'],
            'valor_iva_blister'         => $row['22'],
            'valor_iva_unidad'          => $row['23'],
            'iva_product'               => $row['17'],
            'expiration'                => Null,

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
    if (is_null($presentacion)) {
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


}





