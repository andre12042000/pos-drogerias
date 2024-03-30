<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Cash;
use App\Models\Orders;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $fecha_actual = date('Y-m');
        $filter_fecha = $request->input('mes_anio');
        $hoy = Carbon::now();
        $mes_actual = $hoy->format('m');
        $year_actual = $hoy->format('Y');

        if($filter_fecha == '' || $filter_fecha == null ){
            $currentMonth = date('Y-m');
        }else{
            $currentMonth = $filter_fecha;
        }


        $mes_actual = ucfirst(utf8_encode(\Carbon\Carbon::now()->locale('es')->monthName));



        $cashes = Cash::select(DB::raw('DAY(created_at) as day'), 'cashesable_type', DB::raw('SUM(quantity) as total'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->groupBy('day', 'cashesable_type')
            ->get();

        $data = [];

        foreach (range(1, 31) as $day) {
            $ventas = $cashes->where('day', $day)->where('cashesable_type', 'App\Models\Sale')->sum('total');
            $abonos = $cashes->where('day', $day)->where('cashesable_type', 'App\Models\Abono')->sum('total');
            $data[] = [
                'day' => $day,
                'ventas' => $ventas,
                'abonos' => $abonos
            ];
        }

        $total_ingresos = $cashes->sum('total');
        $cantidad_ventas = $cashes->where('cashesable_type', 'App\Models\Sale')->sum('total');
        $cantidad_abonos = $cashes->where('cashesable_type', 'App\Models\Abono')->sum('total');
        $cantidad_compras = $this->obtenercantidadcompras($currentMonth);
        $cantidad_deuda = $this->obtenerdeudas();
        $cantidad_gastos = $this->obtenercantidadgastos($currentMonth);
        $cantidad_consumo = $this->obtenercantidadconsumointerno($currentMonth);


        $topProducts = $this->obtenerproductosmasvendidos();
        $ventas_ultimos_meses = $this->obtenerventasultimosmeses();
        $compras_ultimos_meses = $this->obtenercomprasultimosmeses();

        $months = $ventas_ultimos_meses['months'];
        $totals = $ventas_ultimos_meses['totals'];

        $purchasemonths = $compras_ultimos_meses['months'];
        $purchasetotals = $compras_ultimos_meses['totals'];

        return view('home', compact('fecha_actual','filter_fecha', 'cantidad_consumo', 'cantidad_gastos', 'purchasemonths', 'purchasetotals', 'data', 'total_ingresos', 'mes_actual', 'topProducts', 'cantidad_ventas', 'cantidad_abonos', 'cantidad_compras', 'cantidad_deuda', 'months', 'totals'));

    }

    public function actilizarestadisticas(Request $request){

        return $this->index($request);

    }

    function obtenercantidadgastos($currentMonth)
    {
        $gastos = DB::table('gastos')
                        ->where('status', '=', 'APLICADO')
                        ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $currentMonth)
                        ->sum('total');

        if(is_null($gastos)){
            $total = 0;
        }else{
            $total = $gastos;
        }

        return $total;
    }

    function obtenercantidadconsumointerno($currentMonth)
    {
        $consumos = DB::table('consumo_internos')
                        ->where('status', '=', 'APLICADA')
                        ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $currentMonth)
                        ->sum('total');
        if(is_null($consumos)){
            $total = 0;
        }else{
            $total = $consumos;
        }

        return $total;
    }

    function obtenercantidadcompras($currentMonth)
    {
        $cantidad_compras = DB::table('purchases')
                        ->where('status', '=', 'APLICADO')
                        ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $currentMonth)
                        ->sum('total');

        if(is_null($cantidad_compras)){
            $total = 0;
        }else{
            $total = $cantidad_compras;
        }

        return $total;
    }

    function obtenerdeudas()
    {
        $deudas = Orders::sum('saldo');

        if(is_null($deudas)){
            $total = null;
        }else{
            $total = $deudas;
        }

        return $total;
    }

// no se actializan con el mes del imput

    function obtenerproductosmasvendidos()
    {


      $topProducts = DB::table('sale_details')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->select('sale_details.product_id', 'products.name', 'products.code', 'products.stock', DB::raw('SUM(sale_details.quantity) as total_quantity'))
            ->groupBy('sale_details.product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();


        return $topProducts;
    }

    function obtenerventasultimosmeses()
    {
        $months = [];
        $totals = [];

        for ($i = 6; $i >= 1; $i--) {
            $date = Carbon::now()->subMonths($i)->format('Y-m');
            $months[] = $date;
            $total = Sale::selectRaw('SUM(total) as total')
                        ->whereMonth('created_at', '=', $i)
                        ->whereYear('created_at', '=', Carbon::now()->year)
                        ->first();
            $totals[] = $total->total ?? 0;

            }

        $datos = ['months' => $months, 'totals' => $totals];

        return $datos;

    }

    function obtenercomprasultimosmeses()
    {
        $months = [];
        $totals = [];

        for ($i = 6; $i >= 1; $i--) {
            $date = Carbon::now()->subMonths($i)->format('Y-m');
            $months[] = $date;
            $total =  Purchase::selectRaw('SUM(total) as total')
                        ->whereMonth('created_at', '=', $i)
                        ->whereYear('created_at', '=', Carbon::now()->year)
                        ->first();
            $totals[] = $total->total ?? 0;

            }

        $datos = ['months' => $months, 'totals' => $totals];

        return $datos;

    }
}
