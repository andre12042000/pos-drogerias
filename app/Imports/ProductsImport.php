<?php

namespace App\Imports;

use App\Models\Inventario;
use App\Models\Product;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Ubicacion;
use App\Models\UnidadMedida;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Events\NuevoProductoCreado;

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


    if(!is_null($row['0'])){

            if($row['3'] == ''){
                $laboratorio = 1;

            }else{
                $laboratorio = $this->crearlaboratorios($row['3']);
            }

            if($row['4'] == ''){
                $ubicacion = 1;
            }else{
                $ubicacion = $this->crearubicacion($row['4']);
            }

            if($row['2'] == ''){
                $presentacion =  1;
            }else{
                $presentacion = $this->crearpresentaciones($row['2']);
            }


            return  new Product([
                'code'                      => $row['0'],
                'name'                      => $row['1'],
                'stock'                     => 0,
                'stock_min'                 => 0,
                'stock_max'                 => 0,
                'image'                     => 'imag/sinimagen.jpg',
                'precio_caja'               => $row['25'],
                'precio_blister'            => 0,
                'precio_unidad'             => 0,
                'status'                    => 'ACTIVE',
                'category_id'               => 1,
                'medida_id'                 => 1,
                'brand_id'                  => 1,
                'laboratorio_id'            => $laboratorio,
                'presentacion_id'           => $presentacion,
                'ubicacion_id'              => $ubicacion,
                'exento'                    => $row['5'],
                'excluido'                  => $row['6'],
                'no_gravado'                => $row['7'],
                'gravado'                   => $row['8'],
                'contenido_interno_caja'    => $row['9'],
                'contenido_interno_blister' => $row['10'],
                'contenido_interno_unidad'  => $row['11'],
                'inventario_caja'           => $row['12'],
                'inventario_blister'        => $row['13'],
                'inventario_unidad'         => $row['15'],
                'costo_caja'                => $row['17'],
                'costo_blister'             => $row['18'],
                'costo_unidad'              => $row['19'],
                'valor_iva_caja'            => $row['20'],
                'valor_iva_blister'         => $row['21'],
                'valor_iva_unidad'          => $row['22'],
                'iva_product'               => $row['16'],
                'disponible_caja'           => 1,
                'disponible_blister'        => 1,
                'disponible_unidad'         => 1,

            ]);

        }



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





