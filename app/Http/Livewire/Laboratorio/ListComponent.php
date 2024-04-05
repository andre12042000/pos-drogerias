<?php

namespace App\Http\Livewire\Laboratorio;

use App\Models\Product;
use Livewire\Component;
use App\Models\Laboratorio;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadlaboratorios'];
    public $buscar, $search;

    public function reloadlaboratorios()
    {
        $this->render();
    }

    public function render()
    {
        $laboratorios = Laboratorio::search($this->search)->orderBy('name', 'ASC')
            ->paginate($this->cantidad_registros);


        return view('livewire.laboratorio.list-component', compact('laboratorios'))->extends('adminlte::page');
    }

    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
    }

    public function sendData($laboratorio)
    {
        $this->emit('LaboratorioEvent', $laboratorio);
    }

    public function destroy($id)
    {

        $products = Product::where('laboratorio_id', $id)->first();

        if ($products) {
            session()->flash('warning', 'Este laboratorio esta siendo utilizada no se puede eliminar');
            return view('livewire.laboratorio.list-component');
        } else {
            $presentacion = Laboratorio::find($id);
            $presentacion->delete();
            session()->flash('delete', 'Laboratorio eliminado exitosamente');
            return view('livewire.laboratorio.list-component');
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
