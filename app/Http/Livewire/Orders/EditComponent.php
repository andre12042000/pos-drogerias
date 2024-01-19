<?php

namespace App\Http\Livewire\Orders;

use App\Models\Orders;
use App\Models\Empresa;
use App\Models\Product;
use Livewire\Component;
use App\Models\Provider;
use App\Models\OrdersDetails;
use App\Models\HistorialAsiganacion;
use Illuminate\Support\Facades\Auth;

class EditComponent extends Component
{
    public $full_numero, $descripcion, $valor, $abono, $saldo, $client_id, $provider_id, $status, $client_name, $fecha, $id_order, $details, $detail_abonos, $order;
    public $error_search, $precio, $total_descuento, $total_venta;
    public $productssale = [];
    public $showEdit  = false;
    public  $cantidad_item, $total_item, $item_update, $precio_unitario_item, $asignado, $codigo_de_producto;

    protected $listeners = ['ProductEvent'];
    public function ProductEvent($product, $precio)
    {

        $product = json_decode(json_encode($product));
        $this->precio = $precio;
        $this->verifyProduct($product);
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
            $this->total_venta = $this->total_venta + $productsale['total'];
            $this->total_descuento = $this->total_descuento + $productsale['discount'];
        }

        $this->calcularsaldos();
    }

    public function calcularsaldos()
    {
        $this->saldo = $this->total_venta - $this->abono;
    }
    public function destroyItem($key)
    {

        unset($this->productssale[$key]);
        $this->calcularTotal();
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

    public function agregarproduct()
    {
        if(!empty($this->productssale)){
            foreach($this->productssale as $product){
               OrdersDetails::create([
                    'order_id'      => $this->order->id,
                    'product_id'    => $product['id'],
                    'price'         => $product['price'],
                    'discount'      => 0,
                    'quantity'      => $product['quantity'],
                    'total'         => $product['total'],
               ]);
            }
        }
        $order = Orders::where('id', $this->order->id)->first();
        $total = $this->saldo + $order->valor;
        $saldo = $this->saldo + $order->saldo;
        $order->update([
            'valor'    => $total,
            'saldo'    => $saldo,

        ]);
        return redirect()->route('orders.edit', $order);
        session()->flash('warning', 'Productos añadidos correctamente!');

    }

    public function mount(Orders $order)
    {
        $this->full_numero = $order->full_nro;
        $this->descripcion = $order->descripcion;
        $this->id_order = $order->id;
        $this->valor = $order->valor;
        $this->abono = $order->abono;
        $this->saldo = $order->saldo;
        $this->client_id = $order->client_id;
        $this->status = $order->status;
        $this->client_name = $order->client->name;
        $this->fecha = $order->created_at;
        $this->details = $order->details;
        $this->detail_abonos = $order->abonos;
        $this->order = $order;

        if($order->asignado){
             $this->asignado = $order->asignado->name;
        }else{
            $this->asignado = 'Sin asignar';

        }

    }
    public function render()
    {
        $empresa = Empresa::find(1);
        $historiales = HistorialAsiganacion::where('order_id', $this->id_order)->get();
        return view('livewire.orders.edit-component', compact('empresa', 'historiales'))->extends('adminlte::page');
    }
    public function reloadProductos()
    {
        $this->render();
    }

    public function sendDataAbono($order)
    {

        $type = 'Orden de trabajo';

        $this->emit('DataTransaccionEvent',  $order, $type);
    }

    public function sendOrden($order){

        $this->emit('UpdateEvent',  $order);
    }

    public function cerrar_orden()
    {
        $order = Orders::findorfail($this->id_order);

        $order->update([
            'status'    => 2,
        ]);
        $empresa = Empresa::find(1);
        $this->actualizar();
    }

    public function actualizar(){
        return redirect()->route('orders.index')->with('status', 'Orden cerrada correctamente!');
        session()->flash('warning', 'Orden cerrada correctamente!');
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

}
