<?php

namespace App\Http\Livewire\Facturacion;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;

    public $buscar = '';


    public function render()
    {
        $sales = Sale::search($this->buscar)->orderBy('id', 'DESC')
        ->paginate($this->cantidad_registros);


        return view('livewire.facturacion.listado-component',compact('sales'))->extends('adminlte::page');
    }


    /*--------------------Metodos para limpieza ---------------------*/
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
