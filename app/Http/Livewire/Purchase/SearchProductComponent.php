<?php

namespace App\Http\Livewire\Purchase;

use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Vencimientos;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Log;

class SearchProductComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $tipoBusqueda;
    public $search = '';
    public $code, $name, $laboratorio_id, $presentacion_id, $fecha_vencimiento, $cantidad,  $precio_compra, $iva, $lote, $producto_id, $IngresaComoobsequio;
    public $porcentaje_por_caja = 0;
    public  $porcentaje_por_blister = 0;
    public $porcentaje_por_unidad = 0;
    public $precio_venta_caja, $precio_venta_blister, $precio_venta_unidad;
    public $presentaciones, $disponible_blister, $disponible_unidad;
    public $contenido_interno_unidad = 0;
    public $contenido_interno_blister = 0;
    public $descuento = 0;
    public $venta;


    protected $listeners = ['obtenerVentaEvent', 'submitEvent' => 'recibirDatosModal'];

    function recibirDatosModal($data)
    {
        $this->name = $data['name'];
        $this->lote = $data['lote'];
        $this->fecha_vencimiento = $data['fecha_vencimiento'];
        $this->cantidad = $data['cantidad'];
        $this->precio_compra = $data['precio_compra'];
        $this->iva = $data['iva'];
        $this->descuento = $data['descuento'];
        $this->precio_venta_caja = $data['precio_venta_caja'];
        $this->precio_venta_blister = $data['precio_venta_blister'] != '' ? $data['precio_venta_blister'] : 0;
        $this->precio_venta_unidad = $data['precio_venta_unidad'] != '' ? $data['precio_venta_unidad'] : 0;
        $this->IngresaComoobsequio = $data['ingresaComoObsequio'];

        $this->addProduct();
    }

    function obtenerVentaEvent($purchase)
    {
        if ($purchase) {
            $this->venta = $purchase;
        } else {
            return redirect()->route('inventarios.purchase')->with('error', 'Ops, ocurrio un error, verifique el proceso y vuelva a intentarlo.');
        }
    }

    public function mount()
    {
        $this->buscar();
        $this->presentaciones = Presentacion::orderBy('name', 'asc')->active()->get();
    }


    public function render()
    {
        $productos = Product::with('presentacion')->search($this->search)->active()->paginate(3);

        $laboratorios = Laboratorio::orderBy('name', 'asc')->active()->get();


        return view('livewire.purchase.search-product-component', compact('productos', 'laboratorios'));
    }



    public function buscar()
    {

        if ($this->search) {
            if ($this->tipoBusqueda == 'name') {
            } else {
                $this->buscarProductoCode();
            }
        }
    }

    public function cancel()
    {
        $this->reset();
    }

    function buscarProductoCode()
    {
        $producto = Product::where('code', $this->search)->active()->first();

        if ($producto) {
            $this->seleccionarProducto($producto);
        } else {
        }
    }

    public function seleccionarProducto($producto)
    {
        if (is_array($producto)) {
            $this->producto_id = $producto['id'];
            $this->code = $producto['code'];
            $this->name = $producto['name'];
            $this->laboratorio_id = $producto['laboratorio_id'];
            $this->presentacion_id = $producto['presentacion_id'];
            $this->precio_compra = $producto['costo_caja'];
            $this->iva = $producto['iva_product'];
            $this->porcentaje_por_caja = $producto['presentacion']['por_caja'];
            $this->porcentaje_por_blister = $producto['presentacion']['por_blister'];
            $this->porcentaje_por_unidad = $producto['presentacion']['por_unidad'];
            $this->disponible_blister = $producto['disponible_blister'];
            $this->disponible_unidad =  $producto['disponible_unidad'];

            $this->contenido_interno_unidad = $producto['contenido_interno_unidad'] !== null ? $producto['contenido_interno_unidad'] : 0;
            $this->contenido_interno_blister =  $producto['contenido_interno_blister'] !== null ? $producto['contenido_interno_blister'] : 0;
        } else {
            $this->producto_id = $producto->id;
            $this->code = $producto->code;
            $this->name = $producto->name;
            $this->laboratorio_id = $producto->laboratorio_id;
            $this->presentacion_id = $producto->presentacion_id;
            $this->precio_compra = $producto->costo_caja;
            $this->iva = $producto->iva_product;
            $this->porcentaje_por_caja = $producto->presentacion->por_caja;
            $this->porcentaje_por_blister = $producto->presentacion->por_blister;
            $this->porcentaje_por_unidad = $producto->presentacion->por_unidad;
            $this->disponible_blister =  $producto->disponible_blister !== null ? $producto->disponible_blister : 0;
            $this->disponible_unidad = $producto->disponible_unidad !== null ? $producto->disponible_unidad : 0;

            $this->contenido_interno_unidad = $producto->contenido_interno_unidad !== null ? $producto->contenido_interno_unidad : 0;
            $this->contenido_interno_blister = $producto->contenido_interno_blister !== null ? $producto->contenido_interno_blister : 0;
        }


        $this->dispatchBrowserEvent('calcularDatosEvent', ['presentaciones' => $this->presentaciones]);
    }

    public function addProduct()
    {
        $this->validate([
            'cantidad'              => 'required',
            'precio_compra'         => 'required',
            'iva'                   => 'required',
            'precio_venta_caja'     => 'required',
        ]);

        $this->updateProducto();
        $this->registrarDetalleCompra();
        $this->limpiarModal();
        $this->emit('reloadProducts');
    }

    function limpiarModal()
    {
        $this->producto_id ='';
        $this->code ='';
        $this->name = '';
        $this->laboratorio_id ='';
        $this->presentacion_id = '';
        $this->precio_compra = '';
        $this->iva ='';
        $this->porcentaje_por_caja = '';
        $this->porcentaje_por_blister ='';
        $this->porcentaje_por_unidad = '';
        $this->disponible_blister = '';
        $this->disponible_unidad =  '';
        $this->lote = '';
        $this->fecha_vencimiento = '';
        $this->cantidad = '';
        $this->iva = 0;
        $this->descuento = 0;
        $this->precio_venta_caja = '';
        $this->precio_venta_blister = '';
        $this->precio_venta_unidad = '';
        $this->IngresaComoobsequio = '';
    }

    function registrarDetalleCompra()
    {

        if ($this->IngresaComoobsequio != True) {
            $precioCompra = $this->precio_compra;

            $total_descuento = round($this->obtenerDatosDescuentoConIVA($this->cantidad, $this->precio_compra, $this->descuento, $this->iva));
            $descuento = $this->descuento;
            $subtotal = $this->precio_compra * $this->cantidad;
            $total_iva = round($this->obtenerValorIva($subtotal));
            $tax = $this->iva;
        } else {
            $precioCompra = 0;
            $total_descuento = 0;
            $descuento = 0;
            $tax = 0;
            $mount_tax = 0;
            $total_iva = 0;
        }

        PurchaseDetail::create([
            'purchase_id'       => $this->venta['id'],
            'product_id'        => $this->producto_id,
            'caducidad'         => $this->fecha_vencimiento,
            'lote'              => $this->lote,
            'quantity'          => $this->cantidad,
            'purchase_price'    => $precioCompra,
            'precio_caja'       => $this->precio_venta_caja,
            'precio_blister'    => $this->precio_venta_blister,
            'precio_unidad'     => $this->precio_venta_unidad,
            'discount_tax'      => $total_descuento,
            'discount'          => $descuento,
            'tax'               => $tax,
            'mount_tax'         => $total_iva,
            'esObsequio'        => $this->IngresaComoobsequio,
        ]);
    }



    function obtenerDatosDescuentoConIVA($cantidad, $costo_caja, $porcentaje_descuento, $tasa_iva)
    {
        if ($porcentaje_descuento) {
            $total = $cantidad * $costo_caja;

            // Calcular el monto del descuento
            $descuento = ($porcentaje_descuento / 100) * $total;



            // Calcular el total después del descuento
            $total_descuento = $total - $descuento;

            // Calcular el monto del IVA
            $iva = ($tasa_iva / 100) * $total_descuento;

            // Calcular el total con IVA
            $total_con_iva = $total_descuento + $iva;

            // Puedes devolver el resultado o hacer lo que necesites con $total_con_iva
            return $total_con_iva;
        } else {
            return 0;
        }
    }



    function updateProducto()
    {
        $producto = Product::findorfail($this->producto_id);

        $costo_blister = $this->calcularCostos($this->precio_compra, $producto->contenido_interno_blister);

        $costo_unidad = $this->calcularCostos($this->precio_compra, $producto->contenido_interno_unidad);


        $iva_caja = intval($this->obtenerValorIva($this->precio_compra));
        $iva_blister = intval($this->obtenerValorIva($costo_blister));
        $iva_unidad = intval($this->obtenerValorIva($costo_unidad));

        $producto->update([
            'name'              => $this->name,
            'iva_product'       => $this->iva,
            'valor_iva_caja'    => $iva_caja,
            'valor_iva_blister' => $iva_blister,
            'valor_iva_unidad'  => $iva_unidad,
            'costo_caja'        => $this->precio_compra,
            'costo_blister'     => $costo_blister,
            'costo_unidad'      => $costo_unidad,
            'precio_caja'       => $this->precio_venta_caja,
            'precio_blister'    => $this->precio_venta_blister,
        ]);

        return true;
    }

    function calcularCostos($costo_caja, $cantidad)
    {
        if ($cantidad > 0) {
            $costo = $costo_caja / $cantidad;
            return $costo;
        } else {
            return 0;
        }
    }

    function obtenerValorIva($valor)
    {
        if ($this->iva > 0) {
            // Calcular el valor del IVA
            $valorIva = ($this->iva / 100) * $valor;
            return $valorIva;
        } else {
            return 0;
        }
    }


}
