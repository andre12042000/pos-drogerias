<?php

namespace App\Http\Livewire\Anulacion;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SaleAnulacion;
use App\Models\MotivoAnulacion;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadmotivos'];
    public $buscar, $search;

    public function reloadmotivos()
    {
        $this->render();
    }

    public function render()
    {
        $motivos = MotivoAnulacion::search($this->search)->orderBy('name', 'ASC')->paginate($this->cantidad_registros);

        return view('livewire.anulacion.list-component', compact('motivos'))->extends('adminlte::page');
    }
    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
    }

    public function sendData($motivo_anulacion)
    {
        $this->emit('MotivoAnulacionEvent', $motivo_anulacion);
    }

    public function destroy($id)
    {

        $products = SaleAnulacion::where('motivo_anulacion_id', $id)->first();

        if ($products) {
            session()->flash('warning', 'Este motivo de anulación esta siendo utilizada no se puede eliminar');
            return view('livewire.anulacion.list-component');
        } else {
            $presentacion = MotivoAnulacion::find($id);
            $presentacion->delete();
            session()->flash('delete', 'Motivo anulación eliminado exitosamente');
            return view('livewire.anulacion.list-component');
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
