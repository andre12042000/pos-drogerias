<?php

namespace App\Http\Livewire\Reporte;

use Carbon\Carbon;
use App\Models\Cash;
use App\Models\Sale;
use App\Models\User;
use App\Models\Gastos;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\MetodoPago;
use Livewire\WithPagination;
use App\Traits\ImprimirTrait;
use App\Exports\ExportDatosVentas;
use App\Exports\ExportVentaDiaria;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ReporteDiario extends Component
{

    use WithPagination, ImprimirTrait;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 50;
    public  $buscar;
    public $user;
    public $totales = [];

    public $cantidad = 0;

    public $hoy_imprimir = 0;
    public $totalVenta_imprimir = 0;
    public $totalAbono_imprimir = 0;
    public $OtrosConceptos_imprimir = 0;
    public $facturasAnuladas_imprimir = 0;
    public $metodosDePagoGroup_imprimir = 0;
    public $pagoCreditos_imprimir = 0;
    public $totalGastos_imprimir = 0;
    public $totalConsumoInterno_imprimir = 0;
    public $filtro_operaciones;
    public $search = '';

    public function render()
    {

        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');

        $search = $this->search;
        $usuarios = User::where('status', 'ACTIVO')->get();
        $data = Cash::whereDate('created_at', $hoy)->cajero($this->user)
            ->with('cashesable')->orderBy('id', 'desc')
            ->when($search, function ($query) use ($search) {
                $query->where('cashesable_type', $search);
            });

        $ventas = $data->paginate($this->cantidad_registros);
        $data_obtener_valores = $data->get();

        $tipo_operacion_group = $this->obtenerValorTipoDeOperacion();
        $metodosDePagoGroup = $this->obtenerValoresMetodoDePago();
        $facturasAnuladas = $this->obtenerAnulaciones();

        $totalVenta = $tipo_operacion_group['App\Models\Sale'] ?? 0;
        $totalAbono = $tipo_operacion_group['App\Models\Abono'] ?? 0;
        $OtrosConceptos = $tipo_operacion_group['App\Models\Otros'] ?? 0;
        $pagoCreditos = $tipo_operacion_group['App\Models\PagoCreditos'] ?? 0;
        $totalGastos = $tipo_operacion_group['App\Models\Gastos'] ?? 0;
        $totalConsumoInterno = $tipo_operacion_group['App\Models\ConsumoInterno'] ?? 0;


        /* Convertimos las variables en variables publicas para enviarlas facilmente al informe o a imprimir */
        $this->hoy_imprimir = $hoy;
        $this->totalVenta_imprimir = $totalVenta;
        $this->totalAbono_imprimir = $totalAbono;
        $this->totalGastos_imprimir = $totalGastos;
        $this->totalConsumoInterno_imprimir = $totalConsumoInterno;
        $this->OtrosConceptos_imprimir = $OtrosConceptos;
        $this->pagoCreditos_imprimir = $pagoCreditos;
        $this->facturasAnuladas_imprimir = $facturasAnuladas;
        $this->metodosDePagoGroup_imprimir = $metodosDePagoGroup;



        return view('livewire.reporte.reporte-diario', compact('usuarios', 'ventas', 'hoy', 'totalVenta', 'totalAbono', 'OtrosConceptos', 'metodosDePagoGroup', 'facturasAnuladas', 'pagoCreditos', 'totalGastos', 'totalConsumoInterno'));
    }

    public function updatedFiltroOperaciones($value)
    {

        $this->search = $value;
    }

    public function obtenerValorTipoDeOperacion()
    {
        $ventas = Cash::whereDate('created_at', now()->toDateString())->cajero($this->user)
            ->get();

        $totalPorTipoOperacion = $ventas->groupBy('cashesable_type')->map(function ($group) {
            return $group->sum('quantity');
        });


        return $totalPorTipoOperacion;
    }

    public function obtenerValoresMetodoDePago()
    {
        $todosLosMetodosPago = MetodoPago::where('status', 'ACTIVE')->get();

        $ventas = Sale::with('metodopago')->cajero($this->user)
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
        $anulaciones = Sale::where('status', 'ANULADA')->cajero($this->user)
            ->whereDate('created_at', now()->toDateString())
            ->sum('valor_anulado');

        return $anulaciones;
    }

    function imprimirInforme()
    {

        $reciboBody = [];

        // Sección 1
        $reciboBody[] = [
            'label' => 'TOTAL RECAUDO:',
            'value' => '$ ' . number_format($this->totalVenta_imprimir + $this->pagoCreditos_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'ABONOS:',
            'value' => '$ ' . number_format($this->totalAbono_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'OTROS CONCEPTOS:',
            'value' => '$ ' . number_format($this->OtrosConceptos_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'PAGO CRÉDITOS:',
            'value' => '$ ' . number_format($this->pagoCreditos_imprimir, 0),
        ];

        // Métodos de pago
        foreach ($this->metodosDePagoGroup_imprimir as $nombreMetodoPago => $total) {
            $reciboBody[] = [
                'label' => $nombreMetodoPago . ':',
                'value' => '$ ' . number_format($total, 0),
            ];
        }

        // Agregar un salto de línea para separar las secciones
        $reciboBody[] = ['label' => '', 'value' => ''];

        // Sección 2
        $reciboBody[] = [
            'label' => 'V. ANULADAS:',
            'value' => '$ ' . number_format($this->facturasAnuladas_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'CON. INTERNO:',
            'value' => '$ ' . number_format($this->totalConsumoInterno_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'GASTOS OP.:',
            'value' => '$ ' . number_format($this->totalGastos_imprimir, 0),
        ];

        if ($this->totalGastos_imprimir > 0) {
            $gastos = self::obtenerDetallesGastos();

            $reciboBody[] = ['label' => '', 'value' => ''];

            $reciboBody[] = ['label' => '', 'value' => 'DETALLES DE GASTOS'];

            foreach ($gastos as $gasto) {
                $reciboBody[] = [
                    'label' => $gasto->descripcion,
                    'value' => '$ ' . number_format($gasto->total, 0),
                ];
            }
        }


        // Convertir a JSON si es necesario
        //   $reciboBodyJSON = json_encode($reciboBody);

        $this->imprimirRecibo($reciboBody);
    }

    function obtenerDetallesGastos()
    {
        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');
        $data = Gastos::whereDate('created_at', $hoy)
            ->where('status', 'APLICADO')
            ->orderBy('created_at', 'ASC')
            ->get();


        return $data;
    }

    public function exportarventas(){
        $search = $this->search;
        $hoy = Carbon::now();
        $hoy = $hoy->format('Y-m-d');

        $compras = Purchase::whereDate('created_at', $hoy)->cajero($this->user)->orderBy('id', 'desc');

        $cantidad_registros = 10000;

        $compras_mes = $compras->paginate($cantidad_registros);

        $data = Cash::whereDate('created_at', $hoy)->cajero($this->user)
        ->with('cashesable')
        ->orderBy('id', 'desc')
        ->when($search, function ($query) use ($search) {
            $query->where('cashesable_type', $search);
        });
        $ventas = $data->paginate($cantidad_registros);
        $this->totales[] =[
            'totalventa' => $this->totalVenta_imprimir,
            'totalAbono' => $this->totalAbono_imprimir,
            'totalGastos' => $this->totalGastos_imprimir,
            'totalConsumoInterno' => $this->totalConsumoInterno_imprimir,
            'OtrosConceptos' => $this->OtrosConceptos_imprimir,
            'pagoCreditos' => $this->pagoCreditos_imprimir,
            'facturasAnuladas' => $this->facturasAnuladas_imprimir,
            'metodosDePagoGroup' => $this->metodosDePagoGroup_imprimir,



        ];


    return Excel::download(new ExportDatosVentas($ventas, $this->totales, $compras_mes), 'Reporteventasdia.xlsx');

     }
}
