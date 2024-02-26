<?php

namespace App\Http\Livewire\Sale;

use App\Events\VentaCreditoRealizada;
use App\Models\Cash;
use App\Models\Impresora;
use Livewire\Component;
use App\Models\Client;
use App\Models\Credit;
use App\Models\CreditResidue;
use App\Models\Empresa;
use App\Models\HistoryPayment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\MetodoPago;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\UpdateProduct;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Events\VentaRealizada;
use App\Models\Orders;
use App\Models\OrdersDetails;

class SaleComponent extends Component
{
    use UpdateProduct;

    public $client_id, $client_name, $codigo_de_producto, $valuedescuento, $total_producto, $descontado, $precio_producto, $key, $pago, $cambio, $cantidad_recibida, $saldo, $abona;
    public $error_search = false;
    public $productssale = [];
    public $total_venta = 0;
    public $total_descuento = 0;
    public $metodo_pago = 1;
    public $showadddiscount = false;
    public $showpay = false;
    public $tipo_porcentaje = 'porcentaje';
    public $tipo_transaccion = 'contado';
    public $precio = 0;
    public $prefijo, $nuevo_nro_factura, $nro_factura, $nuevoprecio;
    public $productorandom = false;
    public $impresora = null;
    public $tipo_operacion = 'VENTA';
    public $imprimirecibo = 0;
    public $nuevo_nro_order, $nro_order;

    protected $listeners = ['ClientEvent', 'ProductEvent', 'agregarProductoEvent', 'crearVentaEvent' => 'tipoOperacion'];

    function agregarProductoEvent($product, $opcionSeleccionada)
    {
        if(self::verificarStockInventario($product)){
            $this->dispatchBrowserEvent('agregarProductoAlArrayCode', ['producto' => $product, 'opcionSeleccionada' => $opcionSeleccionada]);
        }else{
            $this->dispatchBrowserEvent('errorProductosStock');
        }
    }

    public function ClientEvent($client)
    {
        $this->client_id    = $client['id'];
        $this->client_name  = ucwords($client['name']);

        if ($this->client_id) {
            self::consultarDeudaClient($this->client_id);
        }
    }

    function consultarDeudaClient($cliente_id)
    {
        $cliente = Client::findOrFail($cliente_id);

        if ($cliente->deuda > 0) {
            $this->dispatchBrowserEvent('notify_client_deuda', ['data' => $cliente]);
        }
    }

    public function ProductEvent($product, $precio)
    {

        $product = json_decode(json_encode($product));
        $this->precio = $precio;
        $this->verifyProduct($product);
    }

    public function Mount()
    {
        $this->impresora = Impresora::where('predeterminada', True)->first();
        $this->client_id = 1;
        $this->client_name = 'Venta Venta Rapida';
    }

    public function render()
    {
        $empresa = Empresa::find(1);
        $metodos_pago = MetodoPago::where('status', 'ACTIVE')->orderBy('id', 'desc')->get();

        return view('livewire.sale.sale-component', compact('empresa', 'metodos_pago'));
    }

    public function Imprimirecibo($venta)
    {

        return redirect()->route('ventas.pos.imprimir.recibo', $venta);
    }

    function verificarStockInventario($product)
    {
        if($product['inventario']['cantidad_caja'] == '0' && $product['inventario']['cantidad_blister'] == '0' && $product['inventario']['cantidad_unidad'] == '0'){
            return false;
        }else{
            return true;
        }

    }


    public function searchProductCode()
    {
        $this->validate([
            'codigo_de_producto'    => 'required|min:3|max:254'
        ]);

        $product = Product::where('code', '=', $this->codigo_de_producto)->with('inventario')->first();

        if ($product) {

            if(self::verificarStockInventario($product)){
                $this->dispatchBrowserEvent('agregarProductoAlArrayCode', ['producto' => $product, 'opcionSeleccionada' => null]);
                $product = '';
                $this->codigo_de_producto = '';
            }else{
                $this->dispatchBrowserEvent('errorProductosStock');
                $product = '';
                $this->codigo_de_producto = '';
            }

            // Emitir el evento Livewire para notificar que se ha encontrado un producto

        } else {
            $this->addError('codigo_de_producto', 'Producto no encontrado');
        }
    }

    function tipoOperacion($dataVenta)
    {

        $this->generarVenta($dataVenta);

    }

    /*----------------Funciones crear venta credito ---------*/

    function generarVentaCredito($dataVenta)
    {
        //  dd($dataVenta);
        $this->validate([
            'client_id'         => 'required',
            'total_venta'       => 'nullable|integer|between:0,10000000',
            'metodo_pago'       => 'required',
        ], [
            'client_id.required'        => 'El campo cliente es requerido',
        ]);

        try {

            DB::transaction(function () use ($dataVenta) {



                $order = Orders::create([
                    'type'              => 'VENTA CRÉDITO',
                    'prefijo'           => $this->prefijo,
                    'nro'               => $this->nuevo_nro_order,
                    'full_nro'          => $this->nro_order,
                    'client_id'         => $this->client_id,
                    'user_id'           => Auth::user()->id,
                    'descripcion'       => 'VENTA CRÉDITO',
                    'valor'             => $dataVenta['granTotal'],
                    'abono'             => 0,
                    'saldo'             => $dataVenta['granTotal'],
                    'provider_id'       => null,
                    'assigned'          => null,
                    'status'            => 2,
                    'equipo_id'         => null,

                ]);

                if (!empty($dataVenta['productosParaVenta'])) {
                    foreach ($dataVenta['productosParaVenta'] as $product) {
                        OrdersDetails::create([
                            'order_id'      => $order->id,
                            'product_id'    => $product['id_producto'],
                            'price'         => $product['precio_unitario'],
                            'discount'      => $product['descuento'],
                            'quantity'      => $product['cantidad'],
                            'total'         => $product['subtotal'],
                            'forma'         => $product['forma'],
                        ]);
                    }
                }

                self::actualizarDeudaCliente($this->client_id, $dataVenta['granTotal']);

                event(new VentaCreditoRealizada($order));

                if ($dataVenta['imprimirRecibo'] > 0) {
                  // $this->Imprimirecibo($venta->id);
                }

                $this->dispatchBrowserEvent('venta-generada', ['venta' => $order->full_nro]);
                //  return redirect('/ventas/pos')->with('venta_exitosa' , $venta->id);


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



    /*--------------Funciones generar venta ------------------*/

    function generarVenta($dataVenta)
    {
        try {

            DB::transaction(function () use ($dataVenta) {

                $prefijo = $this->tipo_operacion == 'VENTA' ? 'RE' : 'VCR';
                $nuevoNro = $this->obtenerProximoNumero($prefijo);
                $full_nro = $prefijo . $nuevoNro;
                $estado = $this->tipo_operacion == 'VENTA' ? 'PAGADA' : 'VENTA CRÉDITO';

                $venta = Sale::create([
                    'prefijo'           => $prefijo,
                    'nro'               => $nuevoNro,
                    'full_nro'          => $full_nro,
                    'client_id'         => $this->client_id,
                    'user_id'           => Auth::user()->id,
                    'sale_date'         => Carbon::now(),
                    'discount'          => $dataVenta['descuentoGlobal'],
                    'tax'               => $dataVenta['ivaTotalGlobal'],
                    'total'             => $dataVenta['granTotal'],
                    'tipo_operacion'    => $this->tipo_operacion,
                    'metodo_pago_id'    => $dataVenta['metodoPago'],
                    'status'            => $estado,
                ]);


                $this->detallesVenta($venta, $dataVenta['productosParaVenta']);

                if ($this->tipo_operacion == 'VENTA') {
                    $this->ventaContado($venta);
                } elseif ($this->tipo_operacion == 'CREDITO') {
                    $this->ventaCredito($venta);
                    $credito =  $this->crearCredito($venta);
                    self::agregarValorDeudaCliente($this->client_id, $dataVenta['granTotal']);
                }

                event(new VentaRealizada($venta));

                if ($dataVenta['imprimirRecibo'] > 0) {
                    $this->Imprimirecibo($venta->id);
                }

                $this->dispatchBrowserEvent('venta-generada', ['venta' => $venta->full_nro]);
                //  return redirect('/ventas/pos')->with('venta_exitosa' , $venta->id);


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

    function crearCredito($venta)
    {
        $credito =  Credit::create([
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

    function detallesVenta($venta, $dataProducts)
    {

        foreach ($dataProducts as $data) {
            SaleDetail::create([
                'sale_id'       => $venta->id,
                'product_id'    => $data['id_producto'],
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




    /*--------------Fin funciones generar venta ------------------*/

    public function close()
    {
        $this->showpay = false;
    }
}
