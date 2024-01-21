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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\UpdateProduct;

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

    protected $listeners = ['ClientEvent', 'ProductEvent'];

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
    }

    public function render()
    {
        $empresa = Empresa::find(1);

        return view('livewire.sale.sale-component', compact('empresa'));
    }



    public function searchProductCode()
    {
        $this->validate([
            'codigo_de_producto'    => 'required|min:3|max:254'
        ]);

        $product = Product::where('code', '=', $this->codigo_de_producto)->first();

        if (!is_null($product)) {
            $this->codigo_de_producto = null;
            $this->precio = $product->sell_price;
            $this->verifyProduct($product);

        } else {
            $this->error_search = 'Producto no encontrado!';
        }
    }

    public function verifyProduct($product) //Verificamos si el producto ya ha sido agregado al array
    {
        $crear_registro = false;

        if (count($this->productssale) > 0) {

            foreach ($this->productssale as $key => $productsale) {
                if ($productsale['code'] == $product->code) {
                    $this->addQuantity($key);
                    $crear_registro = false;
                    break;
                } else {
                    $crear_registro = true;
                }
            }
        } else {
            $crear_registro = true;
        }


        if ($crear_registro) {
            $this->addProduct($product);
        }
    }

    public function addProduct($product)
    {
        $this->productssale[] = [
            'id'            => $product->id,
            'code'          => $product->code,
            'name'          => $product->name,
            'price'         => $this->precio,
            'quantity'      => 1,
            'discount'      => 0,
            'stock'         => $product->stock,
            'total'         => $this->precio,
        ];
        $this->calcularTotal();
        $this->modalopen();

    }

    public function addQuantity($key)
    {
        $this->productssale[$key]['quantity'] = $this->productssale[$key]['quantity'] + 1;
        $this->productssale[$key]['total'] = ($this->productssale[$key]['quantity'] *  $this->productssale[$key]['price']) - $this->productssale[$key]['discount'];
        $this->calcularTotal();
    }

    public function downCantidad($key)
    {
        if ($this->productssale[$key]['quantity'] > 1) {
            $this->productssale[$key]['quantity'] = $this->productssale[$key]['quantity'] - 1;
            $this->productssale[$key]['total'] = ($this->productssale[$key]['quantity'] *  $this->productssale[$key]['price']) - $this->productssale[$key]['discount'];
        } else {
            return false;
        }

        $this->calcularTotal();
    }

    public function destroyItem($key)
    {

        unset($this->productssale[$key]);
        $this->calcularTotal();
    }

    public function calcularTotal()
    {
        $this->total_venta = 0;
        $this->total_descuento = 0;
        foreach ($this->productssale as $productsale) {
            $this->total_venta = $this->total_venta + $productsale['total'];
            $this->total_descuento = $this->total_descuento + $productsale['discount'];
        }
    }

    public function addiscount($key)
    {
        // $this->$valuedescuento, $total_producto
        $this->key = $key;
        $this->total_producto = $this->productssale[$key]['total'];
        $this->precio_producto = $this->total_producto;
        $this->showadddiscount = true;
    }

    public function calcularDescuento()
    {
        $this->validate([
            'valuedescuento'    => 'required|min:1|max:1000000',
        ]);



        if ($this->tipo_porcentaje == 'porcentaje') {
            $descuento = ($this->total_producto * $this->valuedescuento) / 100;
            $newtotal = $this->total_producto - $descuento;
        } elseif ($this->tipo_porcentaje == 'absoluto') {

            $descuento = $this->valuedescuento;
            $newtotal = $this->total_producto - $descuento;
        }

        $this->precio_producto  = $newtotal;
        $this->descontado       = $descuento;
    }

    public function updatedAbona()
    {
       $this->pago = $this->abona;
       $this->saldo = $this->total_venta - $this->abona;
       $this->abona = '';


    }

    public function updatedTipoPorcentaje()
    {
        $this->descontado = 0;
        $this->valuedescuento = 0;
        $this->precio_producto = $this->total_producto;
    }

    public function applyDiscount()
    {

        $this->productssale[$this->key]['discount'] = $this->descontado;
        $this->productssale[$this->key]['total'] =  $this->precio_producto;
        $this->calcularTotal();
        $this->showadddiscount = false;

        $this->cancel();

    }

    public function pay()
    {
        $this->showpay = true;
    }

    public function updateCantidadRecibida()
    {
        if($this->total_venta == '0'){

            return false;
        }

        $this->cambio = $this->cantidad_recibida - $this->total_venta;

    }

    public function close()
    {
        $this->showpay = false;
    }

    public function save()
    {

        if(is_null($this->client_id)){
            $this->client_id = 1;
        }

        if($this->tipo_transaccion == 'contado'){
            $transaccion = 1;
        }else{
            $transaccion = 2;
        }

        $this->obtenernumerofacturacion();

        $sale = Sale::create([
            'prefijo'           => $this->prefijo,
            'nro'               => $this->nuevo_nro_factura,
            'full_nro'          => $this->nro_factura,
            'client_id'         => $this->client_id,
            'user_id'           => Auth::user()->id,
            'sale_date'         => Carbon::now(),
            'discount'          => $this->total_descuento,
            'tax'               => 0,
            'total'             => $this->total_venta,
            'type_sale'         => $transaccion,
            'payment_method'    => $this->metodo_pago,
        ]);

        $this->saveDetailSail($sale);

        if($this->tipo_transaccion == 'contado'){
            $this->ventaContado($sale);
        }elseif($this->tipo_transaccion == 'credito'){
            $this->ventaCredito($sale);
            $credito =  $this->crearCredito($sale);
            $this->historiaPagos($credito);
            $this->registrarSaldo($credito);
        }

        $this->discountProductInventary();

        if(!is_null($this->impresora)){
            $this->abrirCajon();
        }


        return redirect()->route('ventas.pos')->with('venta_exitosa', $sale->id);
    }

    public function abrirCajon(){
        try {
            $printerName = 'MOTOS';
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);
            $openCashDrawerCommand = "\x1B\x70\x00\x40\x50"; // Comando genérico, podría variar
            $printer->text($openCashDrawerCommand);
            $printer->cut();
            $printer->close();
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function obtenernumerofacturacion()
    {
        $empresa = Empresa::findOrFail(1); //Obtener prefijos
        $this->prefijo = $empresa->pre_facturacion;

        $ultimo_numero = Sale::max('nro'); //ultimo numero de facturacion

        if(is_null($this->prefijo))
        {
            $this->prefijo = "";
        }

        if(is_null($ultimo_numero)){
            $ultimo_numero = 0;
        }

        $nuevo_numero = $ultimo_numero + 1;

        $this->nuevo_nro_factura = $nuevo_numero;

        $cantidad_numeros = strlen($nuevo_numero);


        switch ($cantidad_numeros) {
            case 1:
                $nuevo_numero = str_pad($nuevo_numero, 4, "0", STR_PAD_LEFT);
                break;
            case 2:
                $nuevo_numero = str_pad($nuevo_numero, 3, "0", STR_PAD_LEFT);
                break;
            case 3:
                $nuevo_numero = str_pad($nuevo_numero, 2, "0", STR_PAD_LEFT);
                break;
            default:
                $nuevo_numero = str_pad($nuevo_numero, 1, "0", STR_PAD_LEFT);
        }


        $this->nro_factura = $this->prefijo . $nuevo_numero;
    }



    public function discountProductInventary()
    {
        foreach($this->productssale as $product)
        {
           $this->discountProduct($product);
        }
        return true;


    }

    public function crearCredito($sale)
    {
        $credit = Credit::create([
            'sale_id'       => $sale->id,
            'client_id'     => $sale->client_id,
            'active'        => 1,
        ]);

        return $credit;

    }

    public function historiaPagos($credito)
    {
        $pago = HistoryPayment::create([
            'user_id'       => Auth::user()->id,
            'sale_id'       => $credito->sale_id,
            'credit_id'     => $credito->id,
            'price'         => $this->pago,
        ]);
    }

    public function registrarSaldo($credito)
    {
        $saldo = CreditResidue::create([
            'sale_id'       => $credito->sale_id,
            'credit_id'     => $credito->id,
            'saldo'         => $this->saldo,

        ]);

    }

    public function saveDetailSail($sale)
    {

        foreach($this->productssale as $product)
        {
           SaleDetail::create([
               'sale_id'       => $sale['id'],
               'product_id'    => $product['id'],
               'quantity'      => $product['quantity'],
               'price'         => $product['price'],
               'discount'      => $product['discount'],
               'tax'           => 0,
           ]);
        }
        return true;
    }

    public function ventaCredito($sale)
    {
        $sale->cashs()->create([
            'user_id'           => Auth::user()->id,
            'cashesable_id'     => $sale['id'],
            'quantity'          => $this->pago,
        ]);

    }

    public function ventaContado($sale)
    {
        $sale->cashs()->create([
            'user_id'           => Auth::user()->id,
            'cashesable_id'     => $sale['id'],
            'quantity'          => $this->total_venta,
        ]);

    }
    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }

    public function modalexit()
    {
            $this->productorandom = false;

    }
    public function modalopen()
    {
            $this->productorandom = true;

    }

    public function precioproducto(){

        $this->validate([
            'nuevoprecio'    => 'required|min:3|max:1000000',
        ],[
            'nuevoprecio.required'    => 'Este campo es obligatorio',
            'nuevoprecio.min'         => 'Este campo debe ser mayor a 100',
            'nuevoprecio.max'         => 'Este campo debe se menor a 1000000',

        ]);

        foreach ($this->productssale as &$product) {
            if ($product['price'] == 0) {
                $product['price'] = $this->nuevoprecio;
                $product['total'] = $this->nuevoprecio;
            }
        }

        $this->calcularTotal();
    }


}
