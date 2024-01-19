<?php

namespace App\Http\Livewire\Reporte;

use App\Models\Cash;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class ReporteFecha extends Component
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
    public $filter_desde, $filter_hasta;
   public $desde = 0;
   public $hasta = 0;

    public function updatedFilterHasta($value)
    {
        $this->efectivo = 0;
        $this->tarjeta  = 0;
        $this->transferencia = 0;
        $this->cheque = 0;
        $this->deposito = 0;
        $this->total = 0;
        $this->venta = 0;
        $this->abono = 0;
        $this->cantidad = 0;
     
        $this->hasta =  $value;
        
    }
    public function updatedFilterDesde($value)
    {
        $this->efectivo = 0;
        $this->tarjeta  = 0;
        $this->transferencia = 0;
        $this->cheque = 0;
        $this->deposito = 0;
        $this->total = 0;
        $this->venta = 0;
        $this->abono = 0;
        $this->cantidad = 0;
        $this->desde = $value;

    }

    public function render()
    {
      
        if($this->filter_desde != '' && $this->filter_hasta != ''){
            $data = Cash::whereBetween('created_at', [$this->desde, $this->hasta]);

            $ventas = $data->paginate($this->cantidad_registros); //Pagina la busqueda
            $movimientos = $data->get();  //obtiene todos los resultados para realizar los calculos.
            foreach($movimientos as $movimiento)
            {
                $this->calcularpagos($movimiento);
            }

        }else{
            $ventas = '';
        }
       
        return view('livewire.reporte.reporte-fecha', compact('ventas'));
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
