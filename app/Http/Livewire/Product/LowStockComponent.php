<?php

namespace App\Http\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class LowStockComponent extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    /*---- Variables de filtros, cantidad de registro y busqueda -----*/
    public $cantidad_registros = 10;

    public function render()
    {

        $data = Product::orderBy('stock', 'ASC')
                        ->get();

        $categorias = Category::all();

        $productos = [];

        foreach ($data as $product) {

            if($product->stock <= $product->inventario->cantidad_caja){

                $productos[] =
                [
                    'id'             => $product['id'],
                    'code'           => $product['code'],
                    'name'           => $product['name'],
                    'stock'          => $product['inventario']['cantidad_caja'],
                    'stock_min'      => $product['stock_min'],
                    'stock_max'      => $product['stock_max'],
                    'recomendado'    => $product['stock_max'] - $product['inventario']['cantidad_caja'],
                ];
            }

        }




        return view('livewire.product.low-stock-component', compact('productos', 'categorias'))->extends('adminlte::page');
    }
}
