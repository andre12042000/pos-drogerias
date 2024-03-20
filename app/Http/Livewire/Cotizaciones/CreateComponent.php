<?php

namespace App\Http\Livewire\Cotizaciones;

use App\Models\Client;
use App\Models\Product;
use Livewire\Component;

class CreateComponent extends Component
{
    public $buscar;
    public function render()
    {
        $products = Product::with('inventario')
        ->search($this->buscar)
        ->orderBy('name', 'asc')
        ->active()
        ->take(6)
        ->get();

        $clientes = Client::all();
        return view('livewire.cotizaciones.create-component', compact('products', 'clientes'))->extends('adminlte::page');
    }
}
