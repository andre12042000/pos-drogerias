<?php

namespace App\Http\Livewire\Presentacion;

use App\Models\Product;
use Livewire\Component;
use App\Models\Presentacion;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadpresentaciones'];
    public $buscar;

    public function reloadpresentaciones()
    {
        $this->render();
    }
    public function render()
    {

        $presentaciones = Presentacion::search($this->buscar)->orderBy('name', 'ASC')
            ->paginate($this->cantidad_registros);
        return view('livewire.presentacion.list-component', compact('presentaciones'))->extends('adminlte::page');
    }

    public function sendData($presentacion)
    {
        $this->emit('PresentacionEvent', $presentacion);
    }

    public function destroy($id)
    {

        $products = Product::where('presentacion_id', $id)->first();

        if ($products) {
            session()->flash('warning', 'Este tipo producto esta siendo utilizada no se puede eliminar');
            return view('livewire.presentacion.list-component');
        } else {
            $presentacion = Presentacion::find($id);
            $presentacion->delete();
            session()->flash('delete', 'Este tipo producto eliminada exitosamente');
            return view('livewire.presentacion.list-component');
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
