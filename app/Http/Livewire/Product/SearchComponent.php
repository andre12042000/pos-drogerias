<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class SearchComponent extends Component
{
    public $buscar = '';
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $key;

    // Resto del código del componente...

    public function mount($key)
    {
        $this->key = $key;
        $this->listeners[] = 'refreshSearchComponent';
    }

    public function refreshSearchComponent()
    {
        $this->resetPage(); // Reiniciar la página al recargar el componente
    }


    public function render()
    {
        $products = Product::with('inventario')
        ->search($this->buscar)
        ->orderBy('is_combo', 'desc')
        ->orderBy('name', 'asc')
        ->active()
        ->take(10)
        ->get();

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
