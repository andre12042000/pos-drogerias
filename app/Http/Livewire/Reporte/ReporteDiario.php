<?php

namespace App\Http\Livewire\Reporte;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportVentaDiaria;
use Livewire\Component;
use App\Models\Cash;
use Carbon\Carbon;
use Livewire\WithPagination;


class ReporteDiario extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 50;
    public  $buscar;

    public $efectivo = 0;
    public $tarjeta  = 0;
    public $transferencia = 0;
    public $cheque = 0;
    public $deposito = 0;
    public $total = 0;
    public $venta = 0;
    public $abono = 0;
    public $cantidad = 0;




    public function render()
    {
        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');

        $data = Cash::whereDate('created_at', $hoy)->with('cashesable');
        $ventas = $data->paginate($this->cantidad_registros); //Pagina la busqueda

        $movimientos = $data->get(); //obtiene todos los resultados para realizar los calculos.
        foreach($movimientos as $movimiento)
        {
            $this->calcularpagos($movimiento);
        }


        return view('livewire.reporte.reporte-diario', compact('ventas', 'hoy'));
    }


    public function calcularpagos($dato)
    {


        if($dato->cashesable->payment_method == '1'){
            $this->efectivo = $this->efectivo + $dato->quantity;
        }

        if($dato->cashesable->payment_method == '2'){
            $this->tarjeta = $this->tarjeta + $dato->quantity;
        }

        if($dato->cashesable->payment_method == '3'){
            $this->transferencia = $this->transferencia + $dato->quantity;
        }

        if($dato->cashesable->payment_method == '4'){
            $this->cheque = $this->cheque + $dato->quantity;
        }

        if($dato->cashesable->payment_method == '5'){
            $this->deposito = $this->deposito + $dato->quantity;
        }

        /* resultados por tipo venta o abonos */

        if($dato->cashesable_type == 'App\Models\Sale'){
            $this->venta = $this->venta + $dato->quantity;
        }else{
            $this->abono = $this->abono + $dato->quantity;
        }



        $this->cantidad = $this->cantidad + 1;

        $this->total = $this->total + $dato->quantity;
    }
   
}
