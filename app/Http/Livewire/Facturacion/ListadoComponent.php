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
    public $search;


    public function render()
    {
        $sales = Sale::search($this->search)->orderBy('id', 'DESC')
        ->paginate($this->cantidad_registros);


        return view('livewire.facturacion.listado-component',compact('sales'))->extends('adminlte::page');
    }
    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
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
