<?php

namespace App\Http\Livewire\Purchase;

use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProductComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $tipoBusqueda;
    public $search = '';
    public $code, $name, $laboratorio_id, $presentacion_id, $fecha_vencimiento, $cantidad,  $precio_compra, $iva, $precio_venta, $lote, $producto_id;
    public $porcentaje_por_caja = 0;
    public  $porcentaje_por_blister = 0;
    public $porcentaje_por_unidad = 0;
    public $precio_venta_caja, $precio_venta_blister, $precio_venta_unidad;
    public $presentaciones, $disponible_blister, $disponible_unidad;
    public $contenido_interno_unidad = 0;
    public $contenido_interno_blister = 0;
    public $descuento = 0;
    public $venta;


    protected $listeners = ['obtenerVentaEvent'];

    function obtenerVentaEvent($purchase)
    {
        if($purchase){
            $this->venta = $purchase;
        }else{
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
            if($this->tipoBusqueda == 'name'){

            }else{
                $this->buscarProductoCode();

            }

        }



    }

    function buscarProductoCode()
    {
        $producto = Product::where('code', $this->search)->active()->first();

        if($producto){
            $this->seleccionarProducto($producto);
        }else{

        }

    }

    public function seleccionarProducto($producto)
    {
        if(is_array($producto))
        {
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

            $this->contenido_interno_unidad = $producto['contenido_interno_unidad'] !== null ? $producto['contenido_interno_unidad']: 0;
            $this->contenido_interno_blister =  $producto['contenido_interno_blister'] !== null ? $producto['contenido_interno_blister']:0;


        }else{
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
            $this->disponible_blister =  $producto->disponible_blister !== null ? $producto->disponible_blister: 0;
            $this->disponible_unidad = $producto->disponible_unidad !== null ? $producto->disponible_unidad: 0;

            $this->contenido_interno_unidad = $producto->contenido_interno_unidad !== null ? $producto->contenido_interno_unidad: 0;
            $this->contenido_interno_blister = $producto->contenido_interno_blister !== null ? $producto->contenido_interno_blister: 0;
        }


        $this->dispatchBrowserEvent('calcularDatosEvent', ['presentaciones' => $this->presentaciones]);

    }

    public function addProduct()
    {

     /*    $name, $laboratorio_id, $presentacion_id, $fecha_vencimiento, $cantidad,  $precio_compra, $iva, $precio_venta, $lote;
    public $porcentaje_por_caja = 0;
    public  $porcentaje_por_blister = 0;
    public $porcentaje_por_unidad = 0;
    public $precio_venta_caja, $precio_venta_blister, $precio_venta_unidad;
    public $presentaciones, $disponible_blister, $disponible_unidad;
    public $contenido_interno_unidad = 0;
    public $contenido_interno_blister = 0;
    public $descuento = 0;
    public $venta; */


        $this->validate([
            'cantidad'          => 'required',
            'precio_compra'     => 'required',
            'iva'               => 'required',
            'precio_venta'      => 'required',

        ]);

        $this->updateProducto

    }

    function updateProducto()
    {
        $producto = Product::findorfail();

    }

    function addProductosPurchaseDetail()
    {

    }





}
