<?php

namespace App\Http\Livewire\Sale;

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

    protected $listeners = ['ClientEvent', 'ProductEvent', 'agregarProductoEvent', 'crearVentaEvent' => 'tipoOperacion'];

    function agregarProductoEvent($product, $opcionSeleccionada){


        $this->dispatchBrowserEvent('agregarProductoAlArrayCode', ['producto' => $product, 'opcionSeleccionada' => $opcionSeleccionada]);


    }

    public function ClientEvent($client)
    {
        $this->client_id    = $client['id'];
        $this->client_name  = ucwords($client['name']);
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



    public function searchProductCode()
    {
        $this->validate([
            'codigo_de_producto'    => 'required|min:3|max:254'
        ]);

        $product = Product::where('code', '=', $this->codigo_de_producto)->first();

        if ($product) {

            // Emitir el evento Livewire para notificar que se ha encontrado un producto
            $this->dispatchBrowserEvent('agregarProductoAlArrayCode', ['producto' => $product, 'opcionSeleccionada' => null]);
            $product = '';
            $this->codigo_de_producto = '';
        } else {
            $this->addError('codigo_de_producto', 'Producto no encontrado');
        }
    }

    function tipoOperacion($dataVenta)
    {
        if($this->tipo_operacion == 'VENTA'){
            $this->generarVenta($dataVenta);
        }

    }

    /*--------------Funciones generar venta ------------------*/

    function generarVenta($dataVenta)
    {

        try {

            DB::transaction(function () use ($dataVenta) {

              //  dump($dataVenta);

                $prefijo = 'RE';
                $nuevoNro = $this->obtenerProximoNumero($prefijo);
                $full_nro = $prefijo . $nuevoNro;

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
                    'tipo_operacion'    => 'VENTA',
                    'metodo_pago_id'    => $dataVenta['metodoPago'],
                    'status'            => 'PAGADA',
                ]);

                $this->detallesVenta($venta, $dataVenta['productosParaVenta']);

                return redirect('/ventas/pos')->with('venta_exitosa' , $venta->id);


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

    function detallesVenta($venta, $dataProducts)
    {

        foreach($dataProducts as $data){
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




    /*--------------Fin funciones generar venta ------------------*/






    public function close()
    {
        $this->showpay = false;
    }

}
