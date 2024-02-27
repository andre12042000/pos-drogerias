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
use App\Traits\ImprimirTrait;


class ReporteDiario extends Component
{

    use WithPagination, ImprimirTrait;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 50;
    public  $buscar;

    public $cantidad = 0;

    public $hoy_imprimir = 0;
    public $totalVenta_imprimir = 0;
    public $totalAbono_imprimir = 0;
    public $OtrosConceptos_imprimir = 0;
    public $facturasAnuladas_imprimir = 0;
    public $metodosDePagoGroup_imprimir = 0;
    public $pagoCreditos_imprimir = 0;

    public function render()
    {

        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');
        $data = Cash::whereDate('created_at', $hoy)->with('cashesable')->orderBy('id', 'desc');
        $ventas = $data->paginate($this->cantidad_registros);
        $data_obtener_valores = $data->get();

        $tipo_operacion_group = $this->obtenerValorTipoDeOperacion();
        $metodosDePagoGroup = $this->obtenerValoresMetodoDePago();
        $facturasAnuladas = $this->obtenerAnulaciones();

        $totalVenta = $tipo_operacion_group['App\Models\Sale'] ?? 0;
        $totalAbono = $tipo_operacion_group['App\Models\Abono'] ?? 0;
        $OtrosConceptos = $tipo_operacion_group['App\Models\Otros'] ?? 0;
        $pagoCreditos = $tipo_operacion_group['App\Models\PagoCreditos'] ?? 0;


        /* Convertimos las variables en variables publicas para enviarlas facilmente al informe o a imprimir */
        $this->hoy_imprimir = $hoy;
        $this->totalVenta_imprimir = $totalVenta;
        $this->totalAbono_imprimir = $totalAbono;
        $this->OtrosConceptos_imprimir = $OtrosConceptos;
        $this->pagoCreditos_imprimir = $pagoCreditos;
        $this->facturasAnuladas_imprimir = $facturasAnuladas;
        $this->metodosDePagoGroup_imprimir = $metodosDePagoGroup;





        return view('livewire.reporte.reporte-diario', compact('ventas', 'hoy', 'totalVenta', 'totalAbono', 'OtrosConceptos', 'metodosDePagoGroup', 'facturasAnuladas', 'pagoCreditos'));
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

    function imprimirInforme()
    {

        $reciboBody = [];

        // Sección 1
        $reciboBody[] = [
            'label' => 'TOTAL RECAUDO',
            'value' => '$ ' . number_format($this->totalVenta_imprimir + $this->pagoCreditos_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'ABONOS',
            'value' => '$ ' . number_format($this->totalAbono_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'OTROS CONCEPTOS',
            'value' => '$ ' . number_format($this->OtrosConceptos_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'PAGO CRÉDITOS',
            'value' => '$ ' . number_format($this->pagoCreditos_imprimir, 0),
        ];

        // Métodos de pago
        foreach ($this->metodosDePagoGroup_imprimir as $nombreMetodoPago => $total) {
            $reciboBody[] = [
                'label' => $nombreMetodoPago,
                'value' => '$ ' . number_format($total, 0),
            ];
        }

        // Sección 2
        $reciboBody[] = [
            'label' => 'Venta Anulada',
            'value' => '$ ' . number_format($this->facturasAnuladas_imprimir, 0),
        ];


        $reciboBody[] = [
            'label' => 'Consumo Interno',
            'value' => '$ 0',
        ];

        // Convertir a JSON si es necesario
     //   $reciboBodyJSON = json_encode($reciboBody);

        $this->imprimirRecibo($reciboBody);


    }
}
