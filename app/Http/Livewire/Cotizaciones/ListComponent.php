<?php

namespace App\Http\Livewire\Cotizaciones;

use Dompdf\Dompdf;
use App\Models\Empresa;
use Livewire\Component;
use App\Models\Cotizacion;
use Livewire\WithPagination;
use App\Models\CotizacionDetalle;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    public  $buscar, $search;
    protected $listeners = ['reloadCotizaciones'];

    public function reloadCotizaciones()
    {
        $this->render();
    }
    public function render()
    {
        $cotizaciones = Cotizacion::search($this->search)
        ->paginate($this->cantidad_registros);
        return view('livewire.cotizaciones.list-component', compact('cotizaciones'))->extends('adminlte::page');
    }

    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
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
