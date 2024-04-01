<?php

namespace App\Http\Livewire\Product\Components;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;


class SearchProductComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $buscar = '';

    public function render()
    {

        $products = Product::with('inventario')
                ->where('is_materia_prima', 'no')
                ->search($this->buscar)
                ->orderBy('nivel_favoritismo')
                ->orderBy('is_combo', 'desc')
                ->orderBy('name', 'asc')
                ->active()
                ->paginate(10);


        return view('livewire.product.components.search-product-component', compact('products'));
    }

    public function updatedBuscar()
    {
        self::updatingSearch();
    }

       //Metodos necesarios para la usabilidad


       public function updatingSearch(): void
       {
           $this->resetPage();
       }


       public function doAction($action)
       {
           $this->resetInput();
       }

       //método para reiniciar variables
       private function resetInput()
       {
           $this->reset();
       }

       public function beforeDomUpdate($newPage, $perPage, $search)
       {
           $this->resetPage();
       }

       public function cancel()
       {
               $this->reset();
               $this->resetErrorBag();
       }

}
