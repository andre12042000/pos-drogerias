<?php

namespace App\Http\Livewire\Subcategoria;

use App\Models\Product;
use Livewire\Component;
use App\Models\Subcategoria;
use Livewire\WithPagination;

class ListComponent extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadsub'];
    public $buscar;

    public function reloadsub()
    {
        $this->render();
    }
    public function render()
    {

        $Subcategorias = Subcategoria::search($this->buscar)->orderBy('name', 'ASC')
            ->paginate($this->cantidad_registros);
            return view('livewire.subcategoria.list-component', compact('Subcategorias'))->extends('adminlte::page');
    }

    public function sendData($sub)
    {
        $this->emit('SubEvent', $sub);
    }

    public function destroy($id)
    {

        $products = Product::where('subcategoria_id', $id)->first();

        if ($products) {
            session()->flash('warning', 'Subcategoria esta siendo utilizada no se puede eliminar');
            return view('livewire.subcategoria.list-component');
        } else {
            $sub = Subcategoria::find($id);
            $sub->delete();
            session()->flash('delete', 'Subcategoria eliminada exitosamente');
            return view('livewire.subcategoria.list-component');
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
