<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class SearchComponent extends Component
{
    public $buscar;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $products = Product::search($this->buscar)->active('ACTIVE')->paginate('5');

        return view('livewire.product.search-component', compact('products'));
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
