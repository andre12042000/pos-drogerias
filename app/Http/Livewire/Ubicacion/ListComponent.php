<?php

namespace App\Http\Livewire\Ubicacion;

use App\Models\Product;
use App\Models\Ubicacion;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadubicacion'];
    public $buscar;

    public function reloadubicacion()
    {
        $this->render();
    }

    public function render()
    {
        $ubicaciones = Ubicacion::search($this->buscar)->orderBy('name', 'ASC')
        ->paginate($this->cantidad_registros);


        return view('livewire.ubicacion.list-component', compact('ubicaciones'))->extends('adminlte::page');
    }
    public function sendData($ubicacions)
    {
        $this->emit('UbiciacionEvent', $ubicacions);
    }

    public function destroy($id)
    {

        $products = Product::where('ubicacion_id', $id)->first();

        if ($products) {
            session()->flash('warning', 'Esta Ubicación esta siendo utilizada no se puede eliminar');
            return view('livewire.presentacion.list-component');
        } else {
            $presentacion = Ubicacion::find($id);
            $presentacion->delete();
            session()->flash('delete', 'Ubicación eliminada exitosamente');
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

