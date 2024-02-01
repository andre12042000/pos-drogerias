<?php

namespace App\Http\Livewire\Facturacion;

use App\Models\Cash;
use App\Models\MotivoAnulacion;
use App\Models\Sale;
use App\Models\SaleAnulacion;
use Carbon\Carbon;
use Livewire\Component;
use App\Traits\DevolverProductosInventario;
use Illuminate\Support\Facades\DB;

class AnularFacturaComponent extends Component
{
    use DevolverProductosInventario;
    public $sales;
    public $detailsales;
    public $status = 'disabled';
    public $motivo_anulacion = '';

    public function mount($sale_id)
    {
        $this->sales = Sale::findOrFail($sale_id);
        $this->detailsales = $this->sales->saleDetails;

        $currentDate = Carbon::now()->toDateString();
        $saleDate = Carbon::parse($this->sales->created_at)->toDateString();

     //   dd($currentDate, $saleDate);

        if ($currentDate == $saleDate) {
            // Deshabilitar botón y select
            $this->status = '';
        }

    }
    public function render()
    {
        $motivo_anulaciones = MotivoAnulacion::where('status', 'ACTIVE')->get();

        return view('livewire.facturacion.anular-factura-component', compact('motivo_anulaciones'))->extends('adminlte::page');
    }

    public function anularFactura()
    {

        $this->validate([
            'motivo_anulacion'      => 'required',
        ]);


        try {
            DB::beginTransaction();

                $this->devolverProductosInventario();
                $this->actualizarDatos();
                $this->addSaleAnulacion();
                $this->dispatchBrowserEvent('factura-anulada-correctamente');

            DB::commit();

        } catch (\Exception $e) {
            // Manejar la excepción, puedes hacer un rollback en caso de error
            DB::rollback();

            if ($e) {
                $this->dispatchBrowserEvent('error', ['message' => 'Error al anular la factura: ' . $e->getMessage()]);
            } else {
                $this->dispatchBrowserEvent('error', ['message' => 'Se produjo un error inesperado: ' . $e->getMessage()]);
            }

            throw $e;
        }



    }

    function devolverProductosInventario()
    {
        if(count($this->detailsales) > 0){
           $this->devolverproductos($this->detailsales);
        }
    }

    public function actualizarDatos()
    {
        //actualizamos cash
        $cash = $this->sales->cashs->first();


        if ($cash) {
            $cash->update([
                'quantity' => 0,
            ]);
        }

        //actualizamos detalle venta

        $this->sales->saleDetails()->update([
           'quantity'   => 0,
           'price'      => 0,
           'discount'   => 0,
           'tax'        => 0,
        ]);

        //actualizamos venta
        $total_anulado = $this->sales->total;

        $this->sales->update([
            'tax'           => 0,
            'discount'      => 0,
            'total'         => 0,
            'status'        => 'ANULADA',
            'valor_anulado' => $total_anulado,
        ]);

    }

    public function addSaleAnulacion()
    {
        SaleAnulacion::create([
            'sale_id'               => $this->sales->id,
            'motivo_anulacion_id'   => $this->motivo_anulacion,
        ]);

    }
}
