<?php

namespace App\Http\Livewire\Orders;

use Carbon\Carbon;
use App\Models\Orders;
use App\Models\Empresa;
use App\Models\Product;
use Livewire\Component;
use App\Models\Provider;
use App\Models\OrdersDetails;
use App\Traits\HistorialAsignado;
use Spatie\Permission\Models\Role;
use App\Models\HistorialAsiganacion;
use Illuminate\Support\Facades\Auth;

class EditComponent extends Component
{

    use HistorialAsignado;
    public $full_numero, $descripcion, $valor, $abono, $saldo, $client_id, $provider_id, $status, $client_name, $fecha, $id_order, $details, $detail_abonos, $order;
    public $error_search, $precio, $total_descuento, $total_venta;
    public $productssale = [];
    public $showEdit  = false;
    public  $cantidad_item, $total_item, $item_update, $precio_unitario_item, $asignado, $codigo_de_producto;

    protected $listeners = ['ProductEvent', 'guardardetallesordenEvent'];
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

    public function guardardetallesordenEvent($datos)
    {
        // dd($datos); // Se comenta para evitar detener la ejecución.

        if (!empty($datos['productos'])) {
            foreach ($datos['productos'] as $producto) {
                $orderDetail = OrdersDetails::where('order_id', $this->order->id)
                                            ->where('product_id', $producto['producto_id'])
                                            ->first();

                if ($orderDetail) {
                    // Si el producto ya está en la orden, actualizamos la cantidad y el total.
                    $orderDetail->update([
                        'quantity' => $orderDetail->quantity + $producto['cantidad'],
                        'total'    => $orderDetail->total + $producto['total'],
                    ]);
                } else {
                    // Si el producto no está en la orden, lo creamos.
                    OrdersDetails::create([
                        'order_id'   => $this->order->id,
                        'product_id' => $producto['producto_id'],
                        'price'      => $producto['precio_unitario'],
                        'discount'   => $producto['descuento'],
                        'quantity'   => $producto['cantidad'],
                        'total'      => $producto['total'],
                        'forma'      => $producto['forma'],
                    ]);
                }
            }
        }

        // Actualizamos el total y saldo de la orden.
        $order = Orders::where('id', $this->order->id)->first();
        $total = $datos['totales']['total'] + $order->valor;
        $saldo = $datos['totales']['total'] + $order->saldo;
        $order->update([
            'valor' => $total,
            'saldo' => $saldo,
        ]);
        $this->emit('procesoGuardadoCompleto');

        session()->flash('success', 'Productos añadidos correctamente!');
        return redirect()->route('orders.edit', $order);

    }

    public function eliminarproducto($id){

        $detalle = OrdersDetails::find($id);
        $detalle->delete();
        $order = Orders::where('id', $this->order->id)->first();
        $total =  $order->valor - $detalle->total;
        $saldo =  $order->saldo - $detalle->total;

        $order->update([
            'valor' => $total,
            'saldo' => $saldo,
        ]);

        session()->flash('delete', 'Categoría  eliminada exitosamente');
        return redirect()->route('orders.edit', $order);

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


        $this->asignado = $order->assigned;
    }
    public function render()
    {

        $tecnicoRole = Role::where('name', 'Técnico')->first();

        if ($tecnicoRole) {
            // Obtener los usuarios que tienen el rol 'tecnico'
            $tecnicos = $tecnicoRole->users()->get();
            // $usuariosTecnicos = User::role('tecnico')->get();
        }
        if (count($tecnicos) < 1) {
            session()->flash('warning', 'No hay técnicos registrados en el sistema');
        }

        $cotizacion = Orders::where('id', $this->id_order)->first();

        if($cotizacion){

         $cliente = $cotizacion->client_id;
         $detalles = $cotizacion->details;
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



        $empresa = Empresa::find(1);
        $historiales = HistorialAsiganacion::where('order_id', $this->id_order)->get();
        return view('livewire.orders.edit-component', compact('empresa', 'historiales', 'tecnicos'))->extends('adminlte::page');
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

    public function sendOrden($order)
    {

        $this->emit('UpdateEvent',  $order);
    }


    public function updatedAsignado($value)
    {

        if ($value == 'Seleccionar Técnico') {
            session()->flash('warning', 'Debes seleccionar un técncio');
        } else {
            $order = Orders::findorfail($this->id_order);
            if ($order->assigned != $value) {
                $this->CrearHistorial($this->id_order, $value);
                $order->update([
                    'assigned'    => $value,
                ]);
                session()->flash('success', 'Asignación realizada exitosamente!');
            }
        }
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

    public function actualizar()
    {
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
            $this->precio = $product->precio_caja;
            $this->verifyProduct($product);
        } else {
            $this->error_search = 'Producto no encontrado!';
        }
    }
}
