<?php

namespace App\Http\Livewire\Mantenimiento\Equipos;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Mantenimiento\Entities\Equipos;

class ListarComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['reloadEquipos'];
    public $buscar;
    public $cantidad_registros = 10;

    public function render()
    {
        $equipos = Equipos::search($this->buscar)
                        ->paginate($this->cantidad_registros);


        return view('livewire.mantenimiento.equipos.listar-component', compact('equipos'))->extends('adminlte::page');
    }


    public function destroy($id)
    {
            $product = Equipos::find($id);
            $product->delete();
            session()->flash('delete', 'Equipo eliminado exitosamente');
            $this->reloadEquipos();
    }

    public function sendData($equipo)
    {
        $this->emit('EquipoEventEdit', $equipo);
    }

    public function reloadEquipos()
    {
        $this->render();
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
