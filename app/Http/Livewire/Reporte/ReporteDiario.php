<?php

namespace App\Http\Livewire\Reporte;

use Carbon\Carbon;
use App\Models\Cash;
use App\Models\Sale;
use Livewire\Component;
use App\Models\MetodoPago;
use Livewire\WithPagination;
use App\Exports\ExportVentaDiaria;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ReporteDiario extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 50;
    public  $buscar;

    public $efectivo = 0;
    public $qr  = 0;
    public $transferencia = 0;
    public $cheque = 0;
    public $deposito = 0;
    public $total = 0;
    public $venta = 0;
    public $abono = 0;
    public $cantidad = 0;
    public $totalAnulado = 0;
    public $sumatoriasMetodosPago = [];




    public function render()
    {

        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');
        $data = Cash::whereDate('created_at', $hoy)->with('cashesable');
        $ventas = $data->paginate($this->cantidad_registros);
        $data_obtener_valores = $data->get();

        $tipo_operacion_group = $this->obtenerValorTipoDeOperacion();
        $metodosDePagoGroup = $this->obtenerValoresMetodoDePago();
        $facturasAnuladas = $this->obtenerAnulaciones();





        $totalVenta = $tipo_operacion_group['App\Models\Sale'] ?? 0;
        $totalAbono = $tipo_operacion_group['App\Models\Abono'] ?? 0;
        $OtrosConceptos = $tipo_operacion_group['App\Models\Otros'] ?? 0;



        return view('livewire.reporte.reporte-diario', compact('ventas', 'hoy', 'totalVenta', 'totalAbono', 'OtrosConceptos', 'metodosDePagoGroup', 'facturasAnuladas'));
    }

    public function obtenerValorTipoDeOperacion()
    {
        $ventas = Cash::whereDate('created_at', now()->toDateString())
            ->get();

        $totalPorTipoOperacion = $ventas->groupBy('cashesable_type')->map(function ($group) {
            return $group->sum('quantity');
        });


        return $totalPorTipoOperacion;
    }

    public function obtenerValoresMetodoDePago()
    {
        $todosLosMetodosPago = MetodoPago::where('status', 'ACTIVE')->get();

        $ventas = Sale::with('metodopago')
        ->whereDate('created_at', now()->toDateString())
        ->get();



        // Inicializa un array para almacenar los totales por método de pago
        $totalPorMetodoPago = [];

        // Itera sobre todos los métodos de pago y calcula la suma de ventas
        foreach ($todosLosMetodosPago as $metodoPago) {
            $totalPorMetodoPago[$metodoPago->name] = $ventas
                ->where('metodo_pago_id', $metodoPago->id)
                ->sum('total');

        }

        return $totalPorMetodoPago;

    }

    function obtenerAnulaciones()
    {
        $anulaciones = Sale::where('status', 'ANULADA')
                ->whereDate('created_at', now()->toDateString())
                ->sum('valor_anulado');

        return $anulaciones;

    }









}
