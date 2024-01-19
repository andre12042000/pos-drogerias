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

        $data = Product::orderBy('stock', 'ASC')->get();

        $categorias = Category::all();

        $productos = [];

        foreach ($data as $product) {

            if($product->stock_min > 0 & $product->stock <= $product->stock_min){

                $productos[] =
                [
                    'id'             => $product['id'],
                    'code'           => $product['code'],
                    'name'           => $product['name'],
                    'stock'          => $product['stock'],
                    'stock_min'      => $product['stock_min'],
                    'recomendado'    => $product['stock_max'] - $product['stock'],
                ];
            }

        }




        return view('livewire.product.low-stock-component', compact('productos', 'categorias'))->extends('adminlte::page');
    }
}
