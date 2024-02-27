<?php

namespace App\Http\Livewire\Gastos;

use App\Models\CategoryGastos;
use App\Models\Gastos;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadGastos'];
    public $buscar;

    public function reloadGastos()
    {
        $this->render();
    }
    public function render()
    {
        $gastos = Gastos::search($this->buscar)->orderBy('fecha', 'ASC')
        ->paginate($this->cantidad_registros);

        return view('livewire.gastos.list-component', compact('gastos'))->extends('adminlte::page');
    }

    public function sendData($gastos)
    {
        $this->emit('GatosEvent', $gastos);
    }
}
