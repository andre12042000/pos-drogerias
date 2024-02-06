<?php

namespace App\Http\Livewire\Control\Vencimientos;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Vencimientos;

class ListComponent extends Component
{
    public $cantidad_registros = 10;
    public $producto_vencido, $buscar;
    public $status = 'ACTIVE';
    public $seismeses = false;
    public $mayoryear = false;
    public $tresmeses = true;


    protected $listeners = ['EliminarVencimientoEvent'];


    public function render()
    {

            $vencimientos = Vencimientos::search($this->buscar)
            ->estado($this->status)
            ->filtroseis($this->seismeses)
            ->filtroyear($this->mayoryear)
            ->filtrotres($this->tresmeses)
            ->paginate($this->cantidad_registros);



        return view('livewire.control.vencimientos.list-component', compact('vencimientos'))->extends('adminlte::page');
    }

    public function filtrosesis()
    {
        $this->mayoryear = false;
        $this->tresmeses = false;
        $this->seismeses = true;

    }
    public function filtrotres(){
        $this->mayoryear = false;
        $this->seismeses = false;
        $this->tresmeses = true;
    }

    public function filtromasyeard()
    {
        $this->seismeses = false;
        $this->tresmeses = false;
        $this->mayoryear = true;

    }
    public function updatedBuscar(){
        $this->seismeses = false;
        $this->tresmeses = false;
        $this->mayoryear = false;


        if($this->buscar == ''){

        $this->seismeses = false;
        $this->tresmeses = true;
        $this->mayoryear = false;
        $this->render();
      }



    }

    public function data($id)
    {
        $this->producto_vencido = $id;
    }

    public function EliminarVencimientoEvent()
    {

        $estado = Vencimientos::findOrFail($this->producto_vencido);

        if ($estado->status == 'DESACTIVE') {
            $activo = 'ACTIVE';
        } else {
            $activo = 'DESACTIVE';
        }

        $estado->update([
            'status'                => $activo,
        ]);

        $this->dispatchBrowserEvent('confirmacion');


        $this->render();
    }
}
