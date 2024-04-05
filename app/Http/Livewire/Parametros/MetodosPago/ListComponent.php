<?php

namespace App\Http\Livewire\Parametros\MetodosPago;

use App\Models\MetodoPago;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadMetodoPago'];
    public $buscar, $search;

    public function reloadMetodoPago()
    {
        $this->render();
    }

    public function render()
    {
        $metodos = MetodoPago::search($this->search)->orderBy('name', 'ASC')
        ->paginate($this->cantidad_registros);
        return view('livewire.parametros.metodos-pago.list-component', compact('metodos'))->extends('adminlte::page');
    }
    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
    }

    public function sendData($metodo)
    {
        $this->emit('MetodosPagoEvent', $metodo);
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

