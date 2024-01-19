<?php

namespace App\Http\Livewire\Brand;

use App\Models\Brand;
use App\Models\Equipo;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ListbrandComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    public  $buscar;
    protected $listeners = ['reloadBrands', 'destroy'];

    public function reloadBrands()
    {
        $this->render();
    }

    public function render()
    {
        $brands = Brand::search($this->buscar)
                         ->paginate($this->cantidad_registros);

        return view('livewire.brand.list-brand-component', compact('brands'));
    }

    public function sendData($brand)
    {
        $this->emit('brandEvent', $brand);
    }

    public function destroy($id)
    {
        $products = Product::where('brand_id', $id)->first();

        if ($products) {
            session()->flash('warning', 'Esta marca esta siendo utilizada no se puede eliminar');
            return false;
        } else {
            $brand = Brand::find($id);
            $brand->delete();
            session()->flash('message', 'Marca  eliminada exitosamente');
            return true;
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
