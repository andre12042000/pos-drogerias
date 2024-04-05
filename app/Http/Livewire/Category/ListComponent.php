<?php

namespace App\Http\Livewire\Category;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;


class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadcategories'];
    public $buscar, $search;

    public function reloadcategories()
    {
        $this->render();
    }

    public function render()
    {
        $categories = Category::search($this->search)->orderBy('name', 'ASC')
            ->paginate($this->cantidad_registros);

        return view('livewire.category.list-component', compact('categories'));
    }
    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
    }

    public function sendData($category)
    {
        $this->emit('categoryEvent', $category);
    }

    public function destroy($id)
    {

        $products = Product::where('category_id', $id)->first();

        if ($products) {
            session()->flash('warning', 'Categoría esta siendo utilizada no se puede eliminar');
            return view('livewire.category.list-component');
        } else {
            $category = Category::find($id);
            $category->delete();
            session()->flash('delete', 'Categoría  eliminada exitosamente');
            return view('parametros.category.index');
        }
    }


    //Metodos necesarios para la usabilidad


    public function updatingSearch(): void
    {
        $this->gotoPage(1);
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
}
