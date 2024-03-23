<?php

namespace App\Http\Livewire\Sale;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProductComponent extends Component
{


    public $category;
    public $search;


    public function render()
    {

        $products = Product::active()
            ->category($this->category)
            ->search($this->search)
            ->orderBy('is_combo', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(10);

        $categorias = Category::orderBy('name', 'asc')->get();


        return view('livewire.sale.search-product-component', compact('products', 'categorias'));
    }
}
