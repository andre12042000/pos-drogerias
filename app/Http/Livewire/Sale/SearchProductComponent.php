<?php

namespace App\Http\Livewire\Sale;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProductComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';


    public $category;
    public $search;

    public $buscar;

    public function mount()
    {
        $this->resetPage();
    }


    public function render()
    {

        $products = Product::active()
            ->where('is_materia_prima', 'no')
            ->category($this->category)
            ->search($this->buscar)
            ->orderBy('nivel_favoritismo', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(10);

        $categorias = Category::orderBy('name', 'asc')->get();


        return view('livewire.sale.search-product-component', compact('products', 'categorias'));
    }

    public function updatedSearch()
    {
        $this->resetPage();

        $this->buscar = $this->search;
    }


}
