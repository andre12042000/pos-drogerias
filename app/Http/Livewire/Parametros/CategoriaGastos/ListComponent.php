<?php

namespace App\Http\Livewire\Parametros\CategoriaGastos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CategoryGastos;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadCategoriaGastos'];
    public $buscar;

    public function reloadCategoriaGastos()
    {
        $this->render();
    }

    public function render()
    {
        $categorias = CategoryGastos::search($this->buscar)->orderBy('name', 'ASC')
        ->paginate($this->cantidad_registros);
        return view('livewire.parametros.categoria-gastos.list-component', compact('categorias'))->extends('adminlte::page');
    }

    public function sendData($gasto)
    {
        $this->emit('GastoEvent', $gasto);
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
