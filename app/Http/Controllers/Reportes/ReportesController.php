<?php

namespace App\Http\Controllers\Reportes;

use Carbon\Carbon;
use App\Models\Cash;
use App\Models\Sale;
use App\Models\Abono;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ExportVentaFecha;
use PhpParser\Node\Expr\FuncCall;
use App\Exports\ExportVentaDiaria;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class ReportesController extends Controller
{

    public $efectivo = 0;
    public $tarjeta  = 0;
    public $transferencia = 0;
    public $cheque = 0;
    public $deposito = 0;
    public $total = 0;
    public $venta = 0;
    public $abono = 0;
    public $filter_hasta;
    public $filter_desde;

    public function dia()
    {
        $hoy = Carbon::now();

        $hoy = $hoy->format('Y-m-d');

        $movimientos = Cash::whereDate('created_at', $hoy)->with('cashesable')->get();

        foreach($movimientos as $movimiento)
        {
            $this->calcularpagos($movimiento);
        }


         $efectivo      = $this->efectivo;
         $tarjeta       = $this->tarjeta;
         $transferencia = $this->transferencia;
         $cheque        = $this->cheque;
         $deposito      = $this->deposito;
         $total         = $this->total;
         $venta         = $this->venta;
         $abono         = $this->abono;


        return view('reportes.dia', compact('venta', 'abono', 'movimientos',  'efectivo', 'tarjeta', 'transferencia', 'cheque', 'deposito', 'total'));
    }

    public function fecha()
    {
        return view('reportes.fecha');
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


        $this->total = $this->total + $dato->quantity;
    }

    public function export()
    {

    return Excel::download(new ExportVentaDiaria, 'ventadiaria.xlsx');

    }

    public function exportfecha( $desde, $hasta)
    {
      
    return Excel::download(new ExportVentaFecha($desde, $hasta), 'ventafechas.xlsx');

    }

   

}
