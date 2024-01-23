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

    protected $listeners = ['ClientEvent', 'ProductEvent', 'agregarProductoEvent'];

    function agregarProductoEvent($product, $opcionSeleccionada){
        dump($product, $opcionSeleccionada);
        $this->dispatchBrowserEvent('agregarProductoAlArraySearch', ['producto' => $product, 'opcionSeleccionada' => $opcionSeleccionada]);
        $producto = '';
        $opcionSeleccionada = '';

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

        if ($product) {

            // Emitir el evento Livewire para notificar que se ha encontrado un producto
            $this->dispatchBrowserEvent('agregarProductoAlArrayCode', ['producto' => $product]);
            $product = '';
            $this->codigo_de_producto = '';
        } else {
            $this->addError('codigo_de_producto', 'Producto no encontrado');
        }
    }




    public function close()
    {
        $this->showpay = false;
    }

}
