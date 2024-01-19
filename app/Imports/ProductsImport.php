<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    public $precio_tecnico, $precio_distribuidor, $precio_compra;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $this->obtenerprecios($row['8']);

        return new Product([
            'code'                      => $row['1'],
            'name'                      => $row['3'],
            'stock'                     => $row['4'],
            'stock_min'                 => $row['5'],
            'stock_max'                 => $row['6'],
            'image'                     => $row['7'],
            'sell_price'                => $row['8'],
            'sell_price_tecnico'        => $this->precio_tecnico,
            'sell_price_distribuidor'   => $this->precio_distribuidor,
            'status'                    => $row['9'],
            'last_price'                => $this->precio_compra,
            'category_id'               => 1,
            'medida_id'                 => 1,
            'brand_id'                  => 1,
            'expiration'                => Null,
        ]);
    }

    public function obtenerprecios($sell_price)
    {

       $this->precio_compra = ($sell_price - (($sell_price * 80) / 100)); 
       $this->precio_tecnico = ($sell_price - (($sell_price * 10) / 100)); 
       $this->precio_distribuidor = ($sell_price - (($sell_price * 15) / 100)); 



    }
}





