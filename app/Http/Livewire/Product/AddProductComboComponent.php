<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;

class AddProductComboComponent extends Component
{
    public $buscar;


    public function render()
    {
        $products = Product::with('inventario')
            ->search($this->buscar)
            ->orderBy('name', 'asc')
            ->active()
            ->take(3)
            ->get();


        return view('livewire.product.add-product-combo-component', compact('products'));
    }
}
