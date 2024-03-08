<?php

namespace App\Http\Livewire\Cotizaciones;

use App\Models\Cotizacion;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    public  $buscar;
    protected $listeners = ['reloadCotizaciones'];

    public function reloadCotizaciones()
    {
        $this->render();
    }
    public function render()
    {
        $cotizaciones = Cotizacion::search($this->buscar)
        ->paginate($this->cantidad_registros);
        return view('livewire.cotizaciones.list-component', compact('cotizaciones'))->extends('adminlte::page');
    }


    public function sendData($cotizacion)
    {
        $this->emit('CotizacionEvent', $cotizacion);
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
