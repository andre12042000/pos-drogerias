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
        $products = Product::search($this->buscar)
                            ->orderBy('name', 'asc')
                            ->active()
                            ->paginate('10');

        return view('livewire.product.search-component', compact('products'));
    }

    public function selectProduct($tipo, $product, $precio )
    {
        if($precio > 0){
            $this->emit('ProductEvent',$tipo, $product, $precio);
        }else{
            $this->dispatchBrowserEvent('error-venta-presentacion', ['producto' => $tipo, $product]);
        }

    }
    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


}
