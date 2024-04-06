<?php

namespace App\Http\Livewire\Sale;

use App\Events\ConsumoInternoRealizado;
use App\Events\VentaRealizada;
use App\Models\Cash;
use Livewire\Component;

use App\Models\Client;
use App\Models\ConsumoInterno;
use App\Models\ConsumoInternoDetalles;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Credit;
use App\Models\Empresa;
use App\Models\MetodoPago;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleComponent extends Component
{
    public $empresa, $clientes, $metodos_pago;
    public $error_search = false;


    protected $listeners = ['almacenarTransaccion', 'buscarCotizacion'];

    public function buscarCotizacion($codigo)
    {

       $cotizacion = Cotizacion::where('full_nro', $codigo)->first();

       if($cotizacion){

        $cliente = $cotizacion->client_id;
        $detalles = $cotizacion->saleDetails;
        $productos = [];

        foreach($detalles as $detalle){

            $item = $detalle->product;
            $total = (($detalle->quantity * $detalle->price) + $detalle->tax) -  $detalle->discount;

            $tax = $detalle->tax;

            if ($tax === '0.00') {
                $tax = 0;
            }else {
                $tax = intval($tax);
            }

            $productos[] = [
                'cantidad'          => $detalle->quantity,
                'key'               => Carbon::now(),
                'code'              => $item->code,
                'descuento'         => $detalle->discount,
                'forma'             => $detalle->forma,
                'iva'               => $tax,
                'nombre'            => $item->name,
                'precio_unitario'   => $detalle->price,
                'producto_id'       => $item->id,
                'total'             => $total,
            ];
        }

        $data = [
            'cliente'   => $cliente,
            'productos' => $productos,
        ];

            $this->dispatchBrowserEvent('datos-cotizacion', ['datos' => $data]);

       }else{
            $mensaje = 'No hay cotización disponible';
         $this->dispatchBrowserEvent('error-busqueda', ['mensaje' => $mensaje]);
       }
    }

    function obtenerDetallesCotizacion()
    {

    }

    public function Mount()
    {
        $this->empresa = Empresa::find(1);
        $this->clientes = Client::orderBy('name', 'asc')->get();
        $this->metodos_pago = MetodoPago::where('status', 'ACTIVE')->orderBy('id', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.sale.sale-component');
    }

    public function almacenarTransaccion($data)
    {

        $tipo_operacion = $data['tipoOperacion'];

        switch ($tipo_operacion) {
            case "VENTA":
                self::guardarTipoVenta($data);
                break;
            case "CREDITO":
                self::guardarTipoVenta($data);
                break;
            case "CONSUMO_INTERNO":
                self::guardarConsumoInterno($data);
                break;
            case "COTIZACION":
                self::guardarCotizacion($data);
                break;
        }
    }

    /*---------------------Proceso para consumo interno -------------------------*/

    function guardarConsumoInterno($data)
    {
         try {

            DB::transaction(function () use ($data) {


                $prefijo = 'CIN';
                $nuevoNro = self::obtenerProximoNumeroConsumoInterno($prefijo);
                $full_nro = $prefijo . $nuevoNro;


                $transaccion =  ConsumoInterno::create([
                    'prefijo'       => $prefijo,
                    'nro'           => $nuevoNro,
                    'full_nro'      => $full_nro,
                    'user_id'       =>  Auth::user()->id,
                    'total'         =>  0,
                    'status'        => 'APLICADA',
                ]);

               $detalles =  self::guardarDetallesConsumo($transaccion, $data['productos']);

                $transaccion->update([
                    'total'     => $detalles,
                ]);

                self::crearRegistroCashConsumoInterno($transaccion);

                event(new  ConsumoInternoRealizado($transaccion));

                $this->dispatchBrowserEvent('proceso-guardado', ['venta' => $transaccion->full_nro]);

             });
        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Ops! Ocurrio un error',
                'text' => '¡No es posible crear la transacción, verifica los datos!' . $e,
                'icon' => 'error'
            ]);

            report($e);
        }
    }

    function guardarDetallesConsumo($transaccion, $detalles)
    {
        $total = 0;

        foreach ($detalles as $detalle) {

            $precio_unitario = self::obtenerPrecioCostoProducto($detalle['producto_id'], $detalle['forma'], $detalle['precio_unitario']);

            $subtotal = $detalle['cantidad'] * $precio_unitario;

            $total += $subtotal;

            ConsumoInternoDetalles::create([
                'consumo_interno_id'     => $transaccion->id,
                'product_id'             => $detalle['producto_id'],
                'forma'                  => $detalle['forma'],
                'quantity'               => $detalle['cantidad'],
                'price'                  => $precio_unitario,
            ]);
        }

        return $total;
    }

    function obtenerPrecioCostoProducto($producto_id, $forma, $precio_venta)
    {
        $producto = Product::findOrFail($producto_id);
        $precio_unitario = 0;

        if($forma == 'disponible_unidad'){
            $precio_unitario = $producto->costo_caja;
        }elseif($forma == 'disponible_blister'){
            $precio_unitario = $producto->costo_blister;
        }else{
            $precio_unitario = $producto->costo_unidad;
        }

        //Si en dado caso el precio_unitario esta vacio o igual a 0, se deja el valor original o precio de venta

        if($precio_unitario == '' OR !is_null($precio_unitario) OR $precio_unitario == 0){
            $precio_unitario = $precio_venta;
        }

        return $precio_unitario;

    }

    public static function obtenerProximoNumeroConsumoInterno($prefijo)
    {

        $ultimoRecibo = ConsumoInterno::where('prefijo', $prefijo)
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

    function crearRegistroCashConsumoInterno($transaccion)
    {
        Cash::create([
            'user_id'           => Auth::user()->id,
            'cashesable_id'     => $transaccion->id,
            'cashesable_type'   => 'App\Models\ConsumoInterno',
            'quantity'          => $transaccion->total,
        ]);

    }


    /*-----------------Proceso para cotización -----------------------------------*/

    function guardarCotizacion($data)
    {
        try {

            DB::transaction(function () use ($data) {

            $hoy =  Carbon::now();
            $hoy = $hoy->format('Y-m-d');

            $prefijo = 'CTZ';
            $nuevoNro = self::obtenerProximoNumeroCotizacion($prefijo);
            $full_nro = $prefijo . $nuevoNro;


            $transaccion =  Cotizacion::create([
                'prefijo'            => $prefijo,
                'nro'                => $nuevoNro,
                'full_nro'           => $full_nro,
                'user_id'            =>  Auth::user()->id,
                'client_id'          =>  $data['cliente_id'],
                'cotizacion_date'    =>  $hoy,
                'discount'           =>  $data['totales']['descuentoTotal'],
                'tax'                =>  $data['totales']['ivaTotal'],
                'total'              =>  $data['totales']['total'],
            ]);



            self::guardarDetallesCotizacion($transaccion, $data['productos']);

            $this->dispatchBrowserEvent('proceso-guardado', ['venta' => $transaccion->full_nro]);

            });
        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Ops! Ocurrio un error',
                'text' => '¡No es posible crear la transacción, verifica los datos!' . $e,
                'icon' => 'error'
            ]);

            report($e);
        }


    }

    function guardarDetallesCotizacion($transaccion, $detalles)
    {

        foreach ($detalles as $detalle) {
            CotizacionDetalle::create([
                'cotizacion_id'          => $transaccion->id,
                'product_id'             => $detalle['producto_id'],
                'forma'                  => $detalle['forma'],
                'quantity'               => $detalle['cantidad'],
                'price'                  => $detalle['precio_unitario'],
                'tax'                    => $detalle['iva'],
                'discount'               => $detalle['descuento'],
            ]);
        }
    }

    public static function obtenerProximoNumeroCotizacion($prefijo)
    {

        $ultimoRecibo = Cotizacion::where('prefijo', $prefijo)
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



    /*-----------------------Proceso de venta -------------------------------------*/


    function guardarTipoVenta($dataVenta)  //Incluye proceso de venta y venta crédito
    {
     try {

        $tipo = $dataVenta['tipoOperacion'];

         DB::transaction(function () use ($tipo, $dataVenta) {

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


            $iva = $dataVenta['totales']['ivaTotal'];
            $descuento = $dataVenta['totales']['descuentoTotal'];
            $total = $dataVenta['totales']['total'];

            $client_id = $dataVenta['cliente_id'] === '' ? 1 : $dataVenta['cliente_id'];

            $nuevoNro = $this->obtenerProximoNumero($prefijo);
            $full_nro = $prefijo . $nuevoNro;

            // $observaciones = self::obtenerNombreMesa($dataVenta['resultadoCalculo']['tuplasAPagar']);



            $venta =  Sale::create([
                'prefijo'           => $prefijo,
                'nro'               => $nuevoNro,
                'full_nro'          => $full_nro,
                'client_id'         => $client_id,
                'user_id'           => Auth::user()->id,
                'sale_date'         => Carbon::now(),
                'discount'          => $descuento,
                'tax'               => $iva,
                'total'             => $total,
                'tipo_operacion'    => $tipo_movimiento,
                'metodo_pago_id'    => $metodo_pago,
                'status'            => $estado,
                'observaciones'     => '',
            ]);



            $detalles = $dataVenta['productos'];

            self::detallesVenta($venta, $detalles);


            if ($tipo == 'VENTA') {
                self::ventaContado($venta);
            } elseif ($tipo == 'CREDITO') {
                self::ventaCredito($venta);
                $credito =  self::crearCredito($venta);
                self::agregarValorDeudaCliente($venta->client_id, $venta->total);
            } else {
                self::cortesia($venta);
            }

            event(new  VentaRealizada($venta));

            if ($dataVenta['imprimir'] > 0) {
                self::Imprimirecibo($venta->id);
            }


            $this->dispatchBrowserEvent('proceso-guardado', ['venta' => $venta->full_nro]);

          });
        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Ops! Ocurrio un error',
                'text' => '¡No es posible crear la transacción, verifica los datos!' . $e->getMessage(),
                'icon' => 'error'
            ]);

            report($e->getMessage());
        }
    }

    function detallesVenta($venta, $dataProducts)
    {

        foreach ($dataProducts as $data) {

            SaleDetail::create([
                'sale_id'       => $venta->id,
                'product_id'    => $data['producto_id'],
                'forma'         => $data['forma'],
                'quantity'      => $data['cantidad'],
                'price'         => $data['precio_unitario'],
                'discount'      => $data['descuento'],
                'tax'           => $data['iva'],
            ]);
        }
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

    function crearCredito($venta)
    {
        $credito = Credit::create([
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

    public function Imprimirecibo($venta)
    {

        return redirect()->route('ventas.pos.imprimir.recibo', $venta);
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
}
