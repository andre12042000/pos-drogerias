<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;

class CorreccionProductsComponent extends Component
{
    public function render()
    {
        $unidad = Product::where('disponible_unidad', 1)->where('contenido_interno_unidad', 0)->with('inventario')->get();
        $blisters = Product::where('disponible_blister', 1)->where('contenido_interno_blister', 0)->get();
        $SinInventario = Product::doesntHave('inventario')->get();

        return view('livewire.product.correccion-products-component', compact('unidad','blisters', 'SinInventario'))->extends('adminlte::page');
    }

    public function editarProducto($product)
    {
        $this->dispatchBrowserEvent('abrirModalEdicion', $product);
    }
}
