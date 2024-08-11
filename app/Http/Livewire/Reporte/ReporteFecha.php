<?php

namespace App\Http\Livewire\Reporte;

use App\Models\Cash;
use App\Models\Sale;
use App\Models\User;
use App\Models\Gastos;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\MetodoPago;
use Livewire\WithPagination;
use App\Traits\ImprimirTrait;
use Illuminate\Support\Carbon;
use App\Exports\ExportDatosVentas;
use Maatwebsite\Excel\Facades\Excel;

class ReporteFecha extends Component
{
    use WithPagination, ImprimirTrait;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 50;

    public $filter_desde, $filter_hasta;
    public $desde;
    public $hasta;
    public $user;

    public  $buscar;

    public $cantidad = 0;

    public $hoy_imprimir = 0;
    public $totalVenta_imprimir = 0;
    public $totalAbono_imprimir = 0;
    public $OtrosConceptos_imprimir = 0;
    public $facturasAnuladas_imprimir = 0;
    public $metodosDePagoGroup_imprimir;
    public $pagoCreditos_imprimir = 0;
    public $totalGastos_imprimir = 0;
    public $totalConsumoInterno_imprimir = 0;
    public $filtro_operaciones;
    public $search = '';
    public $totales = [];


    public function mount(){

    }
    public function render()
    {
        $usuarios = User::where('status', 'ACTIVO')->get();
        $search = $this->search;

        $data = Cash::whereBetween('created_at', [$this->desde, $this->hasta])->cajero($this->user)
                ->with('cashesable')
                ->orderBy('id', 'desc')
                ->when($search, function ($query) use ($search) {
                    $query->where('cashesable_type', $search);
                });

        $count = Cash::whereBetween('created_at', [$this->desde, $this->hasta])->cajero($this->user)
                ->with('cashesable')
                ->orderBy('id', 'desc')
                ->when($search, function ($query) use ($search) {
                    $query->where('cashesable_type', $search);
                })
                ->count();

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
        $this->hoy_imprimir = $this->desde . ' ' . $this->hasta;
        $this->totalVenta_imprimir = $totalVenta;
        $this->totalAbono_imprimir = $totalAbono;
        $this->totalGastos_imprimir = $totalGastos;
        $this->totalConsumoInterno_imprimir = $totalConsumoInterno;
        $this->OtrosConceptos_imprimir = $OtrosConceptos;
        $this->pagoCreditos_imprimir = $pagoCreditos;
        $this->facturasAnuladas_imprimir = $facturasAnuladas;
        $this->metodosDePagoGroup_imprimir = $metodosDePagoGroup;

        return view('livewire.reporte.reporte-fecha', compact('usuarios', 'count', 'ventas', 'totalVenta', 'totalAbono', 'OtrosConceptos', 'metodosDePagoGroup', 'facturasAnuladas', 'pagoCreditos', 'totalGastos', 'totalConsumoInterno'));
    }



    public function updatedFilterDesde()
    {
        $this->validate([
            'filter_hasta'  => 'required',
        ],['filter_hasta.required' => 'Campo obligatorio']);

        self::reemplazarFiltros();

    }

    public function updatedUser()
    {
        $this->validate([
            'filter_hasta'  => 'required',
            'filter_desde'  => 'required',
        ],[ 'filter_hasta.required' => 'Campo obligatorio',
            'filter_desde.required' => 'Campo obligatorio',
]);


        self::reemplazarFiltros();

    }

    function reemplazarFiltros()
    {
        $this->desde = Carbon::parse($this->filter_desde)->startOfDay();
        $this->hasta = Carbon::parse($this->filter_hasta)->endOfDay();
    }

    public function updatedFilterHasta()
    {
        $this->validate([
            'filter_desde'  => 'required',
        ]);

        self::reemplazarFiltros();

    }

    public function updatedFiltroOperaciones($value)
    {

        $this->search = $value;
    }

    public function obtenerValorTipoDeOperacion()
    {
        $ventas = Cash::whereBetween('created_at', [$this->desde, $this->hasta])->cajero($this->user)
            ->get();

        $totalPorTipoOperacion = $ventas->groupBy('cashesable_type')->map(function ($group) {
            return $group->sum('quantity');
        });


        return $totalPorTipoOperacion;
    }



    function quitarTildes($cadena) {
        $noPermitidos = array(
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N'
        );
        return strtr($cadena, $noPermitidos);
    }

    public function obtenerValoresMetodoDePago()
    {
        $todosLosMetodosPago = MetodoPago::where('status', 'ACTIVE')->get();

        $ventas = Sale::with('metodopago')->cajero($this->user)
            ->whereBetween('created_at', [$this->desde, $this->hasta])
            ->get();



        // Inicializa un array para almacenar los totales por método de pago
        $totalPorMetodoPago = [];

        // Itera sobre todos los métodos de pago y calcula la suma de ventas
        foreach ($todosLosMetodosPago as $metodoPago) {
            $nombreSinTildes = $this->quitarTildes($metodoPago->name);
            $totalPorMetodoPago[$nombreSinTildes] = $ventas
                ->where('metodo_pago_id', $metodoPago->id)
                ->sum('total');
        }

        return $totalPorMetodoPago;
    }

    function obtenerAnulaciones()
    {
        $anulaciones = Sale::where('status', 'ANULADA')->cajero($this->user)
            ->whereBetween('created_at', [$this->desde, $this->hasta])
            ->sum('valor_anulado');

        return $anulaciones;
    }

    function imprimirInforme()
    {
        $reciboBody = [];

        $reciboBody[] = [
            'label' => 'INFORME',
            'value' => 'RANGO DE FECHAS ',
        ];

        $reciboBody[] = [
            'label' => 'Desde',
            'value' => $this->desde,
        ];

        $reciboBody[] = [
            'label' => 'Hasta',
            'value' => $this->hasta,
        ];

        $reciboBody[] = ['label' => '', 'value' => ''];

        // Sección 1
        $reciboBody[] = [
            'label' => 'TOTAL RECAUDO:',
            'value' =>  number_format($this->totalVenta_imprimir + $this->pagoCreditos_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'ABONOS:',
            'value' =>  number_format($this->totalAbono_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'OTROS CONCEPTOS:',
            'value' =>  number_format($this->OtrosConceptos_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'PAGO CRÉDITOS:',
            'value' =>  number_format($this->pagoCreditos_imprimir, 0),
        ];

        $reciboBody[] = ['label' => '', 'value' => ''];




        // Métodos de pago
        foreach ($this->metodosDePagoGroup_imprimir as $nombreMetodoPago => $total) {
            $reciboBody[] = [
                'label' => $nombreMetodoPago . ':',
                'value' =>  number_format($total, 0),
            ];
        }

        // Agregar un salto de línea para separar las secciones
        $reciboBody[] = ['label' => '', 'value' => ''];

        // Sección 2
        $reciboBody[] = [
            'label' => 'V. ANULADAS:',
            'value' =>  number_format($this->facturasAnuladas_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'CON. INTERNO:',
            'value' =>  number_format($this->totalConsumoInterno_imprimir, 0),
        ];

        $reciboBody[] = [
            'label' => 'GASTOS OP.:',
            'value' =>  number_format($this->totalGastos_imprimir, 0),
        ];

        if ($this->totalGastos_imprimir > 0) {
            $gastos = self::obtenerDetallesGastos();

            $reciboBody[] = ['label' => '', 'value' => ''];

            $reciboBody[] = ['label' => '', 'value' => 'DETALLES DE GASTOS'];

            foreach ($gastos as $gasto) {
                $reciboBody[] = [
                    'label' => $gasto->descripcion,
                    'value' =>  number_format($gasto->total, 0),
                ];
            }
        }

        // Convertir a JSON si es necesario
        //   $reciboBodyJSON = json_encode($reciboBody);
        if($this->user == null){
            $cajero = 'Todos';
        }else{
            $cajero =  User::find($this->user);
            $cajero = $cajero->name;

        }

                $this->imprimirRecibo($reciboBody, $cajero);
    }

    function obtenerDetallesGastos()
    {

        $data = Gastos::whereBetween('created_at', [$this->desde, $this->hasta])->cajero($this->user)
            ->where('status', 'APLICADO')
            ->orderBy('created_at', 'ASC')
            ->get();


        return $data;
    }



    public function exportarventas(){
        self::reemplazarFiltros();
        $search = $this->search;


        $compras = Purchase::whereBetween('created_at', [$this->desde, $this->hasta])->orderBy('id', 'desc');

        $cantidad_registros = 10000;

        $compras_mes = $compras->paginate($cantidad_registros);

        $data = Cash::whereBetween('created_at', [$this->desde, $this->hasta])->cajero($this->user)
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


    return Excel::download(new ExportDatosVentas($ventas, $this->totales, $compras_mes), 'Reporteventas.xlsx');

     }
}
