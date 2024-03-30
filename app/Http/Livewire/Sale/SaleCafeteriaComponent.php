<?php

namespace App\Http\Livewire\Sale;

use App\Models\Client;
use App\Models\Credit;
use App\Models\MetodoPago;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Events\VentaCreditoRealizada;
use App\Traits\UpdateProduct;
use App\Events\VentaRealizada;

class SaleCafeteriaComponent extends Component
{
    use UpdateProduct;

    public $client_id, $client_name, $clientes;
    public $tipo_operacion = 'VENTA';
    public $codigo_de_producto = '';
    public $error_search = '';

    public $metodo_pago = 1;

    protected $listeners = ['pagarEvent' => 'ProcesarCompra'];


    function consultarDeudaClient($cliente_id)
    {
        $cliente = Client::findOrFail($cliente_id);

        if ($cliente->deuda > 0) {
            $this->dispatchBrowserEvent('notify_client_deuda', ['data' => $cliente]);
        }

        if ($this->tipo_operacion == 'CRÉDITO') {
            $this->metodo_pago = 3;
        }
    }

    public function mount()
    {
        self::obtenerClientes();
    }

    public function render()
    {

        $metodos_pago = MetodoPago::where('status', 'ACTIVE')->orderBy('id', 'desc')->get();


        return view('livewire.sale.sale-cafeteria-component', compact('metodos_pago'))->extends('adminlte::page');
    }

    function obtenerClientes()
    {
        $this->clientes = '';
        $this->clientes = Client::orderBy('name', 'asc')->get();
    }

    /*------------Metodo para guardar -------------------*/

    public function ProcesarCompra($data)
    {
        if ($data['tipoOperacion'] == 'VENTA') {
            $tipo = 'VENTA';
        } elseif ($data['tipoOperacion'] == 'CREDITO') {
            $tipo = 'CREDITO';
        } else {
            $tipo = 'CORTESIA';
        }

        self::save($tipo, $data);
    }

    function save($tipo, $dataVenta)
    {
        if ($tipo == 'VENTA') {
            $prefijo = 'RE';
            $estado = 'PAGADA';
            $tipo_movimiento = 'VENTA';
            $metodo_pago = $dataVenta['metodoPago'];
        } elseif ($tipo == 'CREDITO') {
            $prefijo = 'VCR';
            $estado = 'VENTA CRÉDITO';
            $tipo_movimiento = 'VENTA CRÉDITO';
            $metodo_pago = self::obtenerMetodoPagoId('CRÉDITO');
        } else {
            $prefijo = 'CRT';
            $estado = 'CORTESIA';
            $tipo_movimiento = 'CORTESIA';
            $metodo_pago = self::obtenerMetodoPagoId('CORTESÍA');
        }

        $iva = $dataVenta['iva'] === '' ? 0 : $dataVenta['iva'];
        $descuento = $dataVenta['descuento'] === '' ? 0 : $dataVenta['descuento'];
        $client_id = $dataVenta['cliente_id'] === '' ? 0 : $dataVenta['cliente_id'];



        $nuevoNro = $this->obtenerProximoNumero($prefijo);
        $full_nro = $prefijo . $nuevoNro;

        $venta = Sale::create([
            'prefijo'           => $prefijo,
            'nro'               => $nuevoNro,
            'full_nro'          => $full_nro,
            'client_id'         => $client_id,
            'user_id'           => Auth::user()->id,
            'sale_date'         => Carbon::now(),
            'discount'          => $descuento,
            'tax'               => $iva,
            'total'             => $dataVenta['total'],
            'tipo_operacion'    => $tipo_movimiento,
            'metodo_pago_id'    => $metodo_pago,
            'status'            => $estado,
        ]);

        $detalles = $dataVenta['resultadoCalculo']['tuplasAPagar'];


        self::detallesVenta($venta, $detalles);

        if ($tipo == 'VENTA') {
            self::ventaContado($venta);
        } elseif ($tipo == 'CREDITO') {
            self::ventaCredito($venta);
            $credito =  self::crearCredito($venta);
            self::agregarValorDeudaCliente($venta->client_id, $dataVenta['total']);
        } else {
            self::cortesia($venta);
        }

        event(new VentaRealizada($venta));

        if ($dataVenta['imprimirRecibo'] > 0) {
            self::Imprimirecibo($venta->id);
        }

        $this->dispatchBrowserEvent('venta-generada', ['venta' => $venta->full_nro, 'tuplas' => $detalles]);
    }

    function crearCredito($venta)
    {
        $credito =   Credit::create([
            'sale_id'   => $venta->id,
            'client_id' => $venta->client_id,
            'active'    => 1,
            'valor'     => $venta->total,
            'abono'     => 0,
            'saldo'     => $venta->total,
        ]);

        return $credito;
    }

    function agregarValorDeudaCliente($cliente_id, $valor_compra)
    {
        $cliente = Client::findOrFail($cliente_id);

        $nuevo_valor_deuda = $cliente->deuda + $valor_compra;

        $cliente->update([
            'deuda'     => $nuevo_valor_deuda,
        ]);

        return true;
    }

    /*-----------------Pasar los datos a caja -----------------------*/

    public function ventaContado($sale)
    {
        $sale->cashs()->create([
            'user_id'           => Auth::user()->id,
            'cashesable_id'     => $sale['id'],
            'quantity'          => $sale['total'],
        ]);
    }

    public function ventaCredito($sale)
    {
        $sale->cashs()->create([
            'user_id'           => Auth::user()->id,
            'cashesable_id'     => $sale['id'],
            'quantity'          => 0,
        ]);
    }

    public function cortesia($sale)
    {
        $sale->cashs()->create([
            'user_id'           => Auth::user()->id,
            'cashesable_id'     => $sale['id'],
            'quantity'          => 0,
        ]);
    }



    function detallesVenta($venta, $dataProducts)
    {

        foreach ($dataProducts as $data) {
            SaleDetail::create([
                'sale_id'       => $venta->id,
                'product_id'    => $data['producto_id'],
                'forma'         => 'disponible_caja',
                'quantity'      => $data['cantidad'],
                'price'         => $data['precio_unitario'],
                'discount'      => 0,
                'tax'           => 0,
            ]);
        }
    }

    function obtenerMetodoPagoId($nombre_metodo)
    {
        $metodo_pago_id = MetodoPago::where('name', $nombre_metodo)->first();

        if ($metodo_pago_id) {
            $metodo_pago = $metodo_pago_id->id;
        } else {
            $metodo_pago = 2;
        }



        return $metodo_pago;
    }

    public static function obtenerProximoNumero($prefijo)
    {

        $ultimoRecibo = Sale::where('prefijo', $prefijo)
            ->orderBy('nro', 'desc')
            ->first();

        if ($ultimoRecibo) {
            // Obtener el número actual y sumar 1
            $numero = (int) $ultimoRecibo->nro + 1;
        } else {
            // Si no hay registros previos, iniciar desde 1
            $numero = 1;
        }

        // Formatear el número con ceros a la izquierda (ej. 001, 002, ..., 199, 200, ...)
        $numeroFormateado = str_pad($numero, 3, '0', STR_PAD_LEFT);

        // Combinar el prefijo y el número formateado

        return $numeroFormateado;
    }

    public function Imprimirecibo($venta)
    {

        return redirect()->route('ventas.pos.imprimir.recibo', $venta);
    }
}
