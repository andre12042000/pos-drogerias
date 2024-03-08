<?php

namespace App\Http\Livewire\ConsumoInterno;

use App\Models\Product;
use Livewire\Component;

class CreateComponent extends Component
{
    protected $listeners = ['agregarProductoConsumoInternoEvent' => 'agregarProductoListado'];

    public $buscar;
    public function render()
    {
        $products = Product::with('inventario')
                    ->search($this->buscar)
                    ->orderBy('name', 'asc')
                    ->active()
                    ->take(5)
                    ->get();


        return view('livewire.consumo-interno.create-component', compact('products'))->extends('adminlte::page');
    }

    public function agregarProductoListado($productData, $selectedOption)
    {
        dd($productData);
    }
}
