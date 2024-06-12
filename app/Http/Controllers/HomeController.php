<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Cash;
use App\Models\Sale;
use App\Models\User;
use App\Models\Abono;
use App\Models\Client;
use App\Models\Gastos;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        if ($filter_fecha == '' || $filter_fecha == null) {
            $currentMonth = date('Y-m');
        } else {
            $currentMonth = $filter_fecha;
        }
        if($currentMonth != $mes_actual){
            Carbon::setLocale('es');
            $fecha = Carbon::createFromFormat('Y-m', $currentMonth);
            $mes_actual = $fecha->format('F');

        }else{
            $mes_actual = ucfirst(utf8_encode(\Carbon\Carbon::now()->locale('es')->monthName));

        }



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
        $clientes = $this->obtenermejorcliente();
        $total_ingresos = $cashes->sum('total');
        $cantidad_ventas = $cashes->where('cashesable_type', 'App\Models\Sale')->sum('total');
        $cantidad_abonos = $cashes->where('cashesable_type', 'App\Models\Abono')->sum('total');
        $cantidad_compras = $this->obtenercantidadcompras($currentMonth);
        $cantidad_deuda = $this->obtenerdeudas();
        $cantidad_gastos = $this->obtenercantidadgastos($currentMonth);
        $cantidad_consumo = $this->obtenercantidadconsumointerno($currentMonth);


        $topProducts = $this->obtenerproductosmasvendidos();
        $MinProducts  = $this->obtenerproductosmenosvendidos();

        $ventas_ultimos_meses = $this->obtenerventasultimosmeses();
        $compras_ultimos_meses = $this->obtenercomprasultimosmeses();
        $gastos_ultimos_meses = $this->obtenergastosultimosmeses();

        $recaudo_cartera = $this->obtenerrecuadocartera();
        $months = $ventas_ultimos_meses['months'];
        $totals = $ventas_ultimos_meses['totals'];

        $gastosmonths = $gastos_ultimos_meses['months'];
        $gastostotals = $gastos_ultimos_meses['totals'];

        $purchasemonths = $compras_ultimos_meses['months'];
        $purchasetotals = $compras_ultimos_meses['totals'];


        $user = Auth::user();



        if ($user->hasRole('Administrador') OR $user->hasRole('administrador')) {
            // El usuario tiene el rol de administrador
            $role = 'Administrador';
            $cantidad_ventas2 = '01098';
        } else {
            // El usuario tiene otro rol o no tiene rol asignado
            $role = 'otro';
            $userId = auth()->id();
            $hoy = Carbon::now();
            $currentDate = Carbon::today(); // Obtiene la fecha de hoy sin la hora


            $cantidad_ventas2 = \App\Models\Cash::where('cashesable_type', 'App\Models\Sale')
            ->where('user_id', $userId)
            ->whereDate('created_at', $currentDate)
            ->with('sale') // Asegurarse de cargar la relación
            ->get()
            ->sum(function ($cash) {
                return $cash->sale->total; // Sumar el campo total de la relación sale
            });

            $cashes = Cash::select(DB::raw('DAY(created_at) as day'), 'cashesable_type', DB::raw('SUM(quantity) as total'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->groupBy('day', 'cashesable_type')
            ->get();
            $currentDay = date('j'); // Obtener el día actual (sin ceros iniciales)

            // Filtrar y sumar los totales de los abonos del día actual
            $cantidad_abonos = $cashes->where('day', $currentDay)
                ->where('cashesable_type', 'App\Models\Abono')
                ->sum('total');



            $cantidad_compras =  $this->cajeroobtenercantidadcompras($currentDate, $userId);
            $cantidad_deuda = $this->cajeroobtenerdeudas($currentDate, $userId);
            $cantidad_gastos = $this->cajeroobtenercantidadgastos($currentDate, $userId);
            $cantidad_consumo = $this->cajeroobtenercantidadconsumointerno($currentDate, $userId);
            $recaudo_cartera = $this->cajeroobtenerrecuadocartera($currentDate, $userId);


        }


        return view('home', compact('cantidad_ventas2', 'role', 'clientes', 'gastostotals', 'gastosmonths', 'MinProducts', 'recaudo_cartera', 'fecha_actual', 'filter_fecha', 'cantidad_consumo', 'cantidad_gastos', 'purchasemonths', 'purchasetotals', 'data', 'total_ingresos', 'mes_actual', 'topProducts', 'cantidad_ventas', 'cantidad_abonos', 'cantidad_compras', 'cantidad_deuda', 'months', 'totals'));
    }

    public function actilizarestadisticas(Request $request)
    {

        return $this->index($request);
    }

    function obtenercantidadgastos($currentMonth)
    {
        $gastos = DB::table('gastos')
            ->where('status', '=', 'APLICADO')
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $currentMonth)
            ->sum('total');

        if (is_null($gastos)) {
            $total = 0;
        } else {
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
        if (is_null($consumos)) {
            $total = 0;
        } else {
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

        if (is_null($cantidad_compras)) {
            $total = 0;
        } else {
            $total = $cantidad_compras;
        }

        return $total;
    }

    function obtenerdeudas()
    {
        $deudas = Orders::sum('saldo');
        $saldo_clientes = Client::sum('deuda');
        if (is_null($deudas)) {
            $total = null;
        } else {
            $total = $deudas + $saldo_clientes;
        }

        return $total;
    }

    function obtenerrecuadocartera()
    {

        $totalQuantity = Cash::where('cashesable_type', 'App\Models\PagoCreditos')->sum('quantity');


        return $totalQuantity;
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

    function obtenerproductosmenosvendidos()
    {
        $topProducts = DB::table('sale_details')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->select('sale_details.product_id', 'products.name', 'products.code', 'products.stock', DB::raw('SUM(sale_details.quantity) as total_quantity'))
            ->groupBy('sale_details.product_id')
            ->orderBy('total_quantity')
            ->take(5)
            ->get();


        return $topProducts;
    }

    function obtenermejorcliente()
    {
        $clientes = DB::table('sales')
        ->select('client_id', DB::raw('SUM(total) as total_compras'))
        ->where('client_id', '!=', 1) // Omitir client_id igual a 1
        ->groupBy('client_id')
        ->orderByDesc('total_compras')
        ->take(5)
        ->get();

    $data = [];

    foreach ($clientes as $cliente) {
        $client = Client::find($cliente->client_id); // Suponiendo que tienes un modelo Client
        $productoMasComprado = SaleDetail::select('product_id', DB::raw('COUNT(*) as total_compras_producto'))
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.client_id', $cliente->client_id)
            ->groupBy('product_id')
            ->orderByDesc('total_compras_producto')
            ->first();

        $nombreProducto = null;
        if ($productoMasComprado) {
            $producto = Product::find($productoMasComprado->product_id);
            $nombreProducto = $producto->name;
        }

        $data[] = [
            'cliente' => $client->name,
            'telefono' => $client->phone,
            'direccion' => $client->address,
            'deuda' => $client->deuda,
            'total_compras' => $cliente->total_compras,
            'producto_mas_comprado' => $nombreProducto,
            'total_compras_producto' => $productoMasComprado ? $productoMasComprado->total_compras_producto : null,
        ];
    }

    return $data;
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

    function obtenergastosultimosmeses()
    {
        $months = [];
        $totals = [];

        for ($i = 6; $i >= 1; $i--) {
            $date = Carbon::now()->subMonths($i)->format('Y-m');
            $months[] = $date;
            $total =  Gastos::selectRaw('SUM(total) as total')
                ->whereYear('created_at', '=', Carbon::now()->subMonths($i)->year)
                ->whereMonth('created_at', '=', Carbon::now()->subMonths($i)->month)
                ->first();
            $totals[] = $total->total ?? 0;
        }

        $datos = ['months' => $months, 'totals' => $totals];

        return $datos;
    }


    //perfil de cajero

    function cajeroobtenercantidadgastos($currentDate, $userId)
    {
        $gastos = DB::table('gastos')
            ->where('status', '=', 'APLICADO')
            ->where('user_id', $userId)
        ->where(DB::raw('DATE(created_at)'), '=', $currentDate)
            ->sum('total');

        if (is_null($gastos)) {
            $total = 0;
        } else {
            $total = $gastos;
        }

        return $total;
    }

    function cajeroobtenercantidadconsumointerno($currentDate, $userId)
    {
        $consumos = DB::table('consumo_internos')
            ->where('status', '=', 'APLICADA')
            ->where('user_id', $userId)
            ->where(DB::raw('DATE(created_at)'), '=', $currentDate)
            ->sum('total');
        if (is_null($consumos)) {
            $total = 0;
        } else {
            $total = $consumos;
        }

        return $total;
    }

    function cajeroobtenercantidadcompras($currentDate, $userId)
    {
        $currentDate = date('Y-m-d');
        $cantidad_compras = DB::table('purchases')
        ->where('status', '=', 'APLICADO')
        ->where('user_id', $userId)
        ->where(DB::raw('DATE(created_at)'), '=', $currentDate)
        ->sum('total');

        if (is_null($cantidad_compras)) {
            $total = 0;
        } else {
            $total = $cantidad_compras;
        }

        return $total;
    }

    function cajeroobtenerdeudas($currentDate, $userId)
    {
        $deudas = Orders::where('user_id', $userId)->where('created_at', $currentDate)->sum('saldo');
        $saldo_clientes = Client::sum('deuda');
        if ($deudas <= 0) {
            $total = null;
        } else {
            $total = $deudas + $saldo_clientes;
        }
        return $total;
    }

    function cajeroobtenerrecuadocartera($currentDate, $userId)
    {

        $totalQuantity = Cash::where('cashesable_type', 'App\Models\PagoCreditos') ->where('user_id', $userId)
        ->where(DB::raw('DATE(created_at)'), '=', $currentDate)->sum('quantity');


        return $totalQuantity;
    }

}
