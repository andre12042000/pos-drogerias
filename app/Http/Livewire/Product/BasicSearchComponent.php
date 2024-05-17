<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class BasicSearchComponent extends Component
{
    public $buscar,$search;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

   public function render()
    {
        $products = Product::with('inventario')
        ->where('is_materia_prima', 'no')
        ->search($this->search)
        ->orderBy('is_combo', 'desc')
        ->orderBy('name', 'asc')
        ->active()
        ->paginate(10);

        return view('livewire.product.basic-search-component', compact('products'));
    }

    public function selectProduct($product, $precio )
    {
        $this->emit('ProductEvent', $product, $precio);
    }
    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }

}
