<?php

namespace App\Http\Livewire\Product;

use Carbon\Carbon;
use App\Models\Product;
use Livewire\Component;
use App\Models\SaleDetail;
use Livewire\WithPagination;
use App\Models\PurchaseDetail;

class ShowComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $action = 1;
    public $producto, $compras, $ventas;
    public $historial_compras, $historial_ventas;

    public function mount( $product)
    {

        $this->producto = Product::where('id', $product)->first();
       // dd($producto);

    }
    public function render()
    {
        $compras = 0;
        $ventas = 0;
        $meses = 0;
        if($this->action == 1){
            $data = $this->obtenerdatosgrafico();
            $compras = $data['total_purchase'];
            $ventas = $data['totals_sale'];
            $meses = $data['months'];
        }elseif($this->action == 2){
            $this->obtenerhistoriaventas();
        }else{
            $this->obtenerhistoriacompras();
        }
/* dd($data['total_purchase'], $data['totals_sale'], $data['months'] );
 */        return view('livewire.product.show-component', compact('meses', 'compras', 'ventas'))->extends('adminlte::page');
    }

    function obtenerdatosgrafico()
{
    $months = [];
    $totals_purchase = [];
    $totals_sale = [];

    for ($i = 6; $i >= 1; $i--) {
        $date = Carbon::now()->subMonths($i)->format('Y-m');
        $months[] = $date;

        $total_purchase = PurchaseDetail::where('product_id', $this->producto->id)
            ->selectRaw('SUM(quantity) as total')
            ->whereMonth('created_at', '=', Carbon::now()->subMonths($i)->month)
            ->whereYear('created_at', '=', Carbon::now()->year)
            ->first();
        $totals_purchase[] = $total_purchase->total ?? 0;

        $total_sale = SaleDetail::where('product_id', $this->producto->id)
            ->selectRaw('SUM(quantity) as total_sal')
            ->whereMonth('created_at', '=', Carbon::now()->subMonths($i)->month)
            ->whereYear('created_at', '=', Carbon::now()->year)
            ->first();
        $totals_sale[] = $total_sale->total_sal ?? 0;
    }

    $datos = ['months' => $months, 'totals_sale' => $totals_sale, 'total_purchase' => $totals_purchase];

    return $datos;
}

    
    

    function obtenerhistoriacompras()
    {
        $this->historial_compras = PurchaseDetail::where('product_id', $this->producto->id)
                                                    ->orderBy('id', 'DESC')
                                                    ->get();
    }

    function obtenerhistoriaventas()
    {
        $this->historial_ventas = SaleDetail::where('product_id', $this->producto->id)
                                    ->orderBy('id', 'DESC')
                                    ->get();



    }

    //Funcionamiento del componente

    public function doAction($action)
    {
        $this->action = $action;
    }
}
