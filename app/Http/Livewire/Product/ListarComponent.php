<?php

namespace App\Http\Livewire\Product;

use App\Models\Category;
use App\Models\Inventario;
use App\Models\Laboratorio;
use App\Models\OrdersDetails;
use App\Models\Presentacion;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;
use App\Models\Subcategoria;
use App\Models\Ubicacion;
use App\Models\Vencimientos;
use Livewire\Component;
use Livewire\WithPagination;

class ListarComponent extends Component
{
    use WithPagination;
    public $products, $buscar, $filter_estado, $filter_category, $search;
    public $cant_caja, $cant_blister, $cant_unidad;

    public $productId,  $stockCajas, $stockBlisters, $stockUnidades;



    protected $paginationTheme = 'bootstrap';

    public $cantidad_registros = 10;

    protected $listeners = ['actualizarInventarioEvent', 'ProductsEvent', 'reloadProductEvent', 'ajusteInventarioEvent'];

    function ajusteInventarioEvent($dataEvent)
    {

        try {

            $product = $this->guardarDataProduct($dataEvent['dataProduct']);
            $this->guardarDatosInventario($dataEvent['datosInventario']);
            $this->addFechaVencimiento($dataEvent['datosInventario']);


            return redirect()->route('inventarios.product')->with('Se ha actualizado correctamente el producto: ' . $product['name']);
        } catch (\Exception $e) {

            $errorCode = $e->getCode();

            $this->dispatchBrowserEvent('alert-error', ['errorCode' => $errorCode]);
        }
    }

    function actualizarInventarioEvent($data)
    {
        try {

            $this->productId      =  $data['productId'];
            $this->stockCajas     =  $data['stockCajas'];
            $this->stockBlisters  =  $data['stockBlisters'];
            $this->stockUnidades  =  $data['stockUnidades'];


            $this->validate([
                'productId'        => 'required',
                'stockCajas'       => 'required|numeric|min:0',
                'stockBlisters'    => 'required|numeric|min:0',
                'stockUnidades'    => 'required|numeric|min:0',
            ]);

            $inventario = Inventario::where('product_id', $this->productId)->first();

            $inventario->update([
                'cantidad_caja'         => $this->stockCajas,
                'cantidad_blister'      => $this->stockBlisters,
                'cantidad_unidad'       => $this->stockUnidades,
            ]);

            return redirect()->route('inventarios.product')->with('success', 'Se ha actualizado correctamente el stock del producto: ' . $inventario->product->name);
        } catch (\Exception $e) {

            $errorCode = $e->getMessage();

            $this->dispatchBrowserEvent('alert-error', ['errorCode' => $errorCode]);
        }
    }

    private function guardarDataProduct($dataProduct)
    {

        $product = Product::findOrFail($dataProduct['id']);

        if ($dataProduct['contenido_interno_blister'] == null) {
            $contenido_interno_blister = 0;
        } else {
            $contenido_interno_blister = $dataProduct['contenido_interno_blister'];
        }

        if ($dataProduct['contenido_interno_unidad'] == null) {
            $contenido_interno_unidad = 0;
        } else {
            $contenido_interno_unidad = $dataProduct['contenido_interno_unidad'];
        }

        $data = $product->update([
            'name'                      => $dataProduct['name'],
            'code'                      => $dataProduct['code'],
            'status'                    => 'ACTIVE',
            'iva_product'               => $dataProduct['iva_product'],
            'valor_iva_caja'            => $dataProduct['valor_iva_caja'],
            'valor_iva_blister'         => $dataProduct['valor_iva_blister'],
            'valor_iva_unidad'          => $dataProduct['valor_iva_unidad'],
            'stock'                     => '0',
            'stock_min'                 => $dataProduct['stock_min'],
            'stock_max'                 => $dataProduct['stock_max'],
            'disponible_caja'           => $dataProduct['disponible_caja'],
            'disponible_blister'        => $dataProduct['disponible_blister'],
            'disponible_unidad'         => $dataProduct['disponible_unidad'],
            'contenido_interno_caja'    => $dataProduct['contenido_interno_caja'],
            'contenido_interno_blister' => $contenido_interno_blister,
            'contenido_interno_unidad'  => $contenido_interno_unidad,
            'costo_caja'                => $dataProduct['costo_caja'],
            'costo_blister'             => $dataProduct['costo_blister'],
            'costo_unidad'              => $dataProduct['costo_unidad'],
            'precio_caja'               => $dataProduct['precio_caja'],
            'precio_blister'            => $dataProduct['precio_blister'],
            'precio_unidad'             => $dataProduct['precio_unidad'],
            'exento'                    => 0,
            'excluido'                  => 0,
            'no_gravado'                => 0,
            'gravado'                   => 0,
            'laboratorio_id'            => $dataProduct['laboratorio_id'] = !"" ? $dataProduct['laboratorio_id'] : 1,
            'ubicacion_id'              => $dataProduct['ubicacion_id'],
            'presentacion_id'           => $dataProduct['presentacion_id'],
            'category_id'               => $dataProduct['category_id'],
            'subcategoria_id'           => $dataProduct['subcategoria_id'],
        ]);



        return $product;
    }

    function addFechaVencimiento($dataEvent)
    {

        if ($dataEvent['fecha_vencimiento'] != '') {

            $fechas_vencimiento = Vencimientos::where('product_id', $dataEvent['product_id'])
                ->where('fecha_vencimiento', $dataEvent['fecha_vencimiento'])
                ->where('status', 'ACTIVE')
                ->first();

            if (!$fechas_vencimiento) {
                Vencimientos::create([
                    'purchase_id'       => null,
                    'product_id'        => $dataEvent['product_id'],
                    'fecha_vencimiento' => $dataEvent['fecha_vencimiento'],
                    'lote'              => $dataEvent['lote'],
                    'status'            => 'ACTIVE',
                    'cantidad_ingresada' => $dataEvent['cantidad_caja'],
                    'cantidad_vendida'  => 0,
                    'cantidad_cajas_stock_anterior' => 0,
                ]);
            }
        }
    }

    private function guardarDatosInventario($datosInventario)
    {
        $inventario = Inventario::where('product_id', $datosInventario['product_id'])->first();

        if ($datosInventario['cantidad_blister'] == null) {
            $cantidad_blister = 0;
        } else {
            $cantidad_blister = $datosInventario['cantidad_blister'];
        }

        if ($datosInventario['cantidad_unidad'] == null) {
            $cantidad_unidad = 0;
        } else {
            $cantidad_unidad = $datosInventario['cantidad_unidad'];
        }

        $inventario->update([
            'cantidad_caja'     => $datosInventario['cantidad_caja'],
            'cantidad_blister'  => $cantidad_blister,
            'cantidad_unidad'   => $cantidad_unidad,
        ]);
        return true;
    }





    public function ProductsEvent()
    {
        $this->products = Product::all();
    }

    public function sendData($product)
    {
        $this->emit('ProductEventEdit', $product);
    }

    public function reloadProductEvent($product)
    {

        $this->render();

        if ($product) {
            $this->dispatchBrowserEvent('nuevo-producto', ['producto' => $product['name']]);
        }
    }

    public function mount()
    {
        $this->resetPage();
    }



    public function render()
    {
        $productos = Product::search($this->search)
            ->status($this->filter_estado)
            ->orderBy('name', 'ASC')
            ->orderBy('status', 'ASC')
            ->paginate($this->cantidad_registros);

        $categorias     = Category::orderBy('name', 'ASC')->get();
        $subcategorias  = Subcategoria::orderBy('name', 'ASC')->get();
        $ubicaciones    = Ubicacion::orderBy('name', 'ASC')->get();
        $presentaciones  = Presentacion::orderBy('name', 'ASC')->get();
        $laboratorios  = Laboratorio::orderBy('name', 'ASC')->get();


        return view('livewire.product.listar-component', compact('laboratorios', 'productos', 'categorias', 'subcategorias', 'ubicaciones', 'presentaciones'));
    }

    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
    }

    public function destroy($id)
    {
        $orden      = OrdersDetails::where('product_id', $id)->first();
        $sales      = SaleDetail::where('product_id', $id)->first();
        $purchase   = PurchaseDetail::where('product_id', $id)->first();
        $inventario = Inventario::where('product_id', $id)->first();

        if ($inventario->cantidad_caja > 0 or $inventario->cantidad_blister > 0 or $inventario->cantidad_unidad > 0) {
            session()->flash('warning', 'El producto esta siendo utilizado no se puede eliminar');
            return false;
        }



        if ($purchase  or $orden or $sales) {
            session()->flash('warning', 'Producto esta siendo utilizado no se puede eliminar');
            $this->render();
        } else {

            $inventario->delete();
            $product = Product::findOrFail($id);
            $product->delete();
            session()->flash('delete', 'Producto eliminado exitosamente');
            $this->render();
        }
    }

    public function datomodal($product)
    {
        $this->emit('ProductstockEventEdit', $product);
    }


    //Metodos necesarios para la usabilidad


    public function updatingSearch(): void
    {
        $this->resetPage();
    }


    public function doAction($action)
    {
        $this->resetInput();
    }

    //método para reiniciar variables
    private function resetInput()
    {
        $this->reset();
    }

    public function beforeDomUpdate($newPage, $perPage, $search)
    {
        $this->resetPage();
    }
}
