<?php

namespace App\Http\Livewire\ConsumoInterno;

use Livewire\Component;
use App\Models\ConsumoInterno;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    public  $buscar, $search;


    public function render()
    {
        $datos = ConsumoInterno::search($this->search)
                                ->orderBy('created_at', 'DESC')
                                ->paginate($this->cantidad_registros);



        return view('livewire.consumo-interno.list-component', compact('datos'))->extends('adminlte::page');
    }
    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
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
