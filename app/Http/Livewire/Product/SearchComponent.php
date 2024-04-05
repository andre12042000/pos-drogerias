<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class SearchComponent extends Component
{
    public $buscar = '';
    public $search;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $key;

    // Resto del código del componente...

    public function mount($key)
    {
        $this->resetPage();
        $this->key = $key;
    }

    public function refreshSearchComponent()
    {
        $this->resetPage(); // Reiniciar la página al recargar el componente
    }


    public function render()
    {
        $products = Product::with('inventario')
                    ->where('is_materia_prima', 'no')
                    ->search($this->search)
                    ->orderBy('is_combo', 'desc')
                    ->orderBy('name', 'asc')
                    ->active()
                    ->paginate(10);

        return view('livewire.product.search-component', compact('products'));
    }

    public function updatedBuscar()
    {
        $this->resetPage();
        $this->search = $this->buscar;
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
