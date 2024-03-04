<?php

namespace App\Http\Livewire\Gastos;

use App\Models\Cash;
use App\Models\Gastos;
use App\Models\GastosDetalles;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditGastoComponent extends Component
{
    public $gasto, $detalles;
    public $totalfull;
    public $gastoDetailsError = false;

    protected $listeners = ['formularioEnviadoEvent' => 'guardarDetalle', 'confirmarEventGasto' => 'finalizar'];

    public function Mount($gasto_id)
    {
        $this->totalfull = 0;
        $this->gasto = Gastos::findOrFail($gasto_id);
        self::getDetallesGasto();


    }
    public function render()
    {
        return view('livewire.gastos.edit-gasto-component')->extends('adminlte::page');
    }

    function getDetallesGasto()
    {
        $this->detalles = GastosDetalles::where('gastos_id', $this->gasto->id)->get();
        self::calcularTotalDetalles();

    }

    function calcularTotalDetalles()
    {
        $this->totalfull = 0;
        foreach($this->detalles as $detalle){
            $this->totalfull = $this->totalfull + $detalle->total;
        }
    }

    function guardarDetalle($datos)
    {
        GastosDetalles::create([
            'gastos_id'         => $this->gasto->id,
            'cantidad'          => $datos['cantidad'],
            'descripcion'       => $datos['descripcion'],
            'precio_unitario'   => $datos['precioUnitario'],
            'total'             => $datos['total'],
        ]);

        self::getDetallesGasto();
        self::actualizarTotalGasto();
    }

    function actualizarTotalGasto()
    {
        self::calcularTotalDetalles();

        $this->gasto->update([
            'total'     => $this->totalfull,
        ]);

    }

    public function destroy($item)
    {

        $item = GastosDetalles::findOrFail($item);

        $item->delete();

        self::getDetallesGasto();
        self::actualizarTotalGasto();

    }

    public function confirmacionaplicar()
    {
        $this->dispatchBrowserEvent('confirmar_cierre_gasto');
    }

    public function finalizar()
    {
        if($this->totalfull == 0){
            $this->gastoDetailsError == 'True';
            return false;
        }

        $this->gasto->update([
            'status'        => 'APLICADO',
        ]);

        self::crearRegistroCash();

        $this->dispatchBrowserEvent('gasto-registrado');
    }

    function crearRegistroCash()
    {
        Cash::create([
            'user_id'           => Auth::user()->id,
            'cashesable_id'     => $this->gasto->id,
            'cashesable_type'   => 'App\Models\Gastos',
            'quantity'          => $this->totalfull,
        ]);

    }
}
