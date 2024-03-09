<?php

namespace App\Http\Controllers\Reportes;

use Carbon\Carbon;
use App\Models\Cash;
use App\Models\Sale;
use App\Models\Abono;
use App\Models\MetodoPago;
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
    public $cantidad = 0;
    public $totalAnulado = 0;
    public $sumatoriasMetodosPago = [];

    public function dia()
    {

        return view('reportes.dia');
    }

    public function fecha()
    {
        return view('reportes.fecha');
    }


    public function calcularpagos($dato)
    {


        // Obtener métodos de pago
        $metodosDePago = $this->obtenermetodosdepago();

        // Si el array $this->sumatoriasMetodosPago está vacío, inicializarlo
        if (empty($this->sumatoriasMetodosPago)) {
            foreach ($metodosDePago as $metodo) {
                $this->sumatoriasMetodosPago[$metodo->name] = 0;
            }
        }

        // Obtener el ID y nombre del método de pago del objeto $dato (ajusta según la estructura de tus datos)
        $metodoPagoId = $dato->cashesable->metodo_pago_id;
        $nombreMetodo = $metodosDePago->where('id', $metodoPagoId)->first()->name;

        // Validar si el método de pago es válido
        if (isset($this->sumatoriasMetodosPago[$nombreMetodo])) {
            // Actualizar la sumatoria para el método de pago correspondiente
            $this->sumatoriasMetodosPago[$nombreMetodo] += $dato->quantity;
        }

        /* resultados por tipo venta o abonos */
        if ($dato->cashesable_type == 'App\Models\Sale') {
            $this->venta = $this->venta + $dato->quantity;
        } else {
            $this->abono = $this->abono + $dato->quantity;
        }

        $this->cantidad = $this->cantidad + 1;
        $this->total = $this->total + $dato->quantity;
        // Ahora puedes pasar $this->sumatoriasMetodosPago a tu vista para mostrar las sumatorias y nombres de los métodos de pago

    }

    public function obtenermetodosdepago()
    {

        $metodos = MetodoPago::where('status', 'ACTIVE')->get();
        return $metodos;
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
