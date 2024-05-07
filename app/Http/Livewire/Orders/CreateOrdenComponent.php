<?php

namespace App\Http\Livewire\Orders;

use App\Models\User;
use App\Models\Client;
use App\Models\Orders;
use App\Models\Empresa;
use App\Models\Product;
use Livewire\Component;
use App\Models\Provider;
use App\Models\OrdersDetails;
use App\Traits\RegistrarAbono;
use App\Traits\HistorialAsignado;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Auth;
use Modules\Mantenimiento\Entities\Equipos;

class CreateOrdenComponent extends Component
{
    use RegistrarAbono, HistorialAsignado;
    public $client_name, $client_id, $precio, $total_descuento, $provider, $descripcion, $user_assigned;
    public $codigo_de_producto, $cantidad_item, $total_item, $item_update, $equipo, $equipo_name, $asignar;

    public $precio_unitario_item;

    public $prefijo, $nuevo_nro_order, $nro_order;

    public $productssale = [];
    public $total_venta = 0;
    public $abono = 0;
    public $saldo = 0;
    public $metodo_pago = 1;
    public $showEdit  = false;
    public $buscar;

    public $error_search = false;


    protected $listeners = ['ClientEvent', 'ProductEvent', 'EquipoEvent'];

    public function ProductEvent($product, $precio)
    {

        $product = json_decode(json_encode($product));
        $this->precio = $precio;
        $this->verifyProduct($product);
    }

    public function ClientEvent($client)
    {
        $this->client_id    = $client['id'];
        $this->client_name  = ucwords($client['name']);
    }

    public function EquipoEvent($equipo)
    {
        $this->equipo    = $equipo['id'];
        $this->equipo_name  = $equipo['serial_placa'];
        $this->dispatchBrowserEvent('close-modal');

    }

    public function render()
    {

        $products = Product::with('inventario')
        ->search($this->buscar)
        ->orderBy('name', 'asc')
        ->active()
        ->take(10)
        ->get();

        $providers = Provider::all();
        $equipos = Equipos::all();


        $tecnicoRole = Role::where('name', 'Técnico')->first();

        if ($tecnicoRole) {
            // Obtener los usuarios que tienen el rol 'tecnico'
            $tecnicos = $tecnicoRole->users()->get();
            // $usuariosTecnicos = User::role('tecnico')->get();
        }
        if (count($tecnicos) < 1) {
            session()->flash('warning', 'No hay técnicos registrados en el sistema');
        }


        return view('livewire.orders.create-orden-component', compact('providers', 'equipos', 'tecnicos', 'products'));
    }

    public function searchProductCode()
    {
        $this->validate([
            'codigo_de_producto'    => 'required|min:3|max:254'
        ]);

        $product = Product::where('code', '=', $this->codigo_de_producto)->first();

        if (!is_null($product)) {
            $this->codigo_de_producto = null;
            $this->precio = $product->precio_caja;
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
    }

    public function calcularTotal()
    {
        $this->total_venta = 0;
        $this->total_descuento = 0;
        foreach ($this->productssale as $productsale) {
            $this->total_venta = round($this->total_venta + $productsale['total']);
            $this->total_descuento = $this->total_descuento + $productsale['discount'];
        }

        $this->calcularsaldos();
    }

    public function addQuantity($key)
    {
        $this->productssale[$key]['quantity'] = $this->productssale[$key]['quantity'] + 1;
        $this->productssale[$key]['total'] = ($this->productssale[$key]['quantity'] *  $this->productssale[$key]['price']) - $this->productssale[$key]['discount'];
        $this->calcularTotal();
    }

    public function updatedAbono()
    {
        $this->calcularsaldos();
    }

    public function updatedTotalVenta()
    {
        $this->calcularsaldos();
    }

    public function calcularsaldos()
    {
        $this->saldo = round($this->total_venta - $this->abono);
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

    public function save()
    {

        if(is_null($this->descripcion) && empty($this->productssale))
        {

            session()->flash('message', 'No se encontró ni descripción ni productos añadidos, es necesario al menos una opción!');
            return false;
        }



        $this->validate([
            'client_id'         => 'required',
            'total_venta'       => 'nullable|integer|between:0,10000000',
            'descripcion'       => 'nullable|min:5|max:254',
            'metodo_pago'       => 'required',
        ],[
            'client_id.required'        => 'El campo cliente es requerido',
        ]);

        $this->obtenernumeroorder();

        $order = Orders::create([
            'prefijo'           => $this->prefijo,
            'nro'               => $this->nuevo_nro_order,
            'full_nro'          => $this->nro_order,
            'client_id'         => $this->client_id,
            'user_id'           => Auth::user()->id,
            'descripcion'       => $this->descripcion,
            'valor'             => $this->total_venta,
            'abono'             => $this->abono,
            'saldo'             => $this->saldo,
            'provider_id'       => $this->provider,
            'assigned'          => $this->user_assigned,
            'status'            => 1,
            'equipo_id'         => $this->equipo,
            'assigned'          => $this->asignar,


        ]);

        if(!empty($this->productssale)){
            foreach($this->productssale as $product){
               OrdersDetails::create([
                    'order_id'      => $order->id,
                    'product_id'    => $product['id'],
                    'price'         => $product['price'],
                    'discount'      => 0,
                    'quantity'      => $product['quantity'],
                    'total'         => $product['total'],
               ]);
            }
        }

        $detalle_abono = [
            'full_nro'          => $this->nro_order,
            'client_id'         => $this->client_id,
            'user_id'           => Auth::user()->id,
            'amount'            => $this->abono,
            'payment_method'    => $this->metodo_pago,
            'abonableble_id'    => $order->id,
            'abonable_type'     => 'App\Models\Orders',
        ];

        $this->AddAbono($detalle_abono);

        $this->CrearHistorial($order->id, $this->asignar);

        return redirect()->route('orders.index')->with('status', 'Orden creada correctamente!');


    }

    public function obtenernumeroorder()
    {
        $empresa = Empresa::findOrFail(1); //Obtener prefijos
        $this->prefijo = $empresa->pre_orden;

        $ultimo_numero = Orders::max('nro'); //ultimo numero de facturacion

        if(is_null($this->prefijo))
        {
            $this->prefijo = "";
        }

        if(is_null($ultimo_numero)){
            $ultimo_numero = 0;
        }

        $nuevo_numero = $ultimo_numero + 1;

        $this->nuevo_nro_order = $nuevo_numero;

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


        $this->nro_order = $this->prefijo . $nuevo_numero;
    }

    /*************************************************************
    ***              Modificar producto antes de vender        ***
    *********************************************************** */

    public function modificarpreciounitario($key, $price, $quantity, $total)
    {
        $this->precio_unitario_item = $price;
        $this->cantidad_item = $quantity;
        $this->total_item = $total;
        $this->item_update = $key;
        $this->showEdit = true;

    }

    public function updatedPrecioUnitarioItem()
    {
        $this->calcularnuevototalitem();
    }

    public function updatedCantidadItem()
    {
        $this->calcularnuevototalitem();
    }

    public function calcularnuevototalitem()
    {
        $this->total_item = $this->precio_unitario_item * $this->cantidad_item;
    }

    public function update_item()
    {
       $this->productssale[$this->item_update]['price'] = $this->precio_unitario_item;
       $this->productssale[$this->item_update]['quantity'] = $this->cantidad_item;
       $this->productssale[$this->item_update]['total'] = $this->total_item;

       $this->calcularTotal();
       $this->close_edit();

    }

    public function close_edit()
    {
        $this->showEdit = false;
        $this->precio_unitario_item = '';
        $this->cantidad_item = '';
        $this->total_item = '';
        $this->item_update = '';

    }


    public function destroyItem($key)
    {

        unset($this->productssale[$key]);
        $this->calcularTotal();
    }

    public function cancel()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
