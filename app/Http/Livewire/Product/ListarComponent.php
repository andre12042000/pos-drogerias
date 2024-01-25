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
use Livewire\Component;
use Livewire\WithPagination;

class ListarComponent extends Component
{
    public $products, $buscar, $filter_estado, $filter_category;

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $cantidad_registros = 10;

    protected $listeners = ['ProductsEvent', 'reloadProductos', 'ajusteInventarioEvent'];

    function ajusteInventarioEvent($dataEvent)
    {
        try {

            $product = $this->guardarDataProduct($dataEvent['dataProduct']);
            $this->guardarDatosInventario($dataEvent['datosInventario']);

            return redirect()->route('inventarios.product')->with('Se ha actualizado correctamente el producto: ' . $product['name'] );

        } catch (\Exception $e) {

            $errorCode = $e->getCode();

            $this->dispatchBrowserEvent('alert-error', ['errorCode' => $errorCode]);
        }
    }

    private function guardarDataProduct($dataProduct)
    {

        $product = Product::findOrFail($dataProduct['id']);

        if($dataProduct['contenido_interno_blister'] == null){
            $contenido_interno_blister = 0;
        }else{
            $contenido_interno_blister = $dataProduct['contenido_interno_blister'];
        }

        if($dataProduct['contenido_interno_unidad'] == null){
            $contenido_interno_unidad = 0;
        }else{
            $contenido_interno_unidad = $dataProduct['contenido_interno_unidad'];
        }

        $data = $product->update([
            'name'                      => $dataProduct['name'],
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
            'laboratorio_id'            => $dataProduct['laboratorio_id'],
            'ubicacion_id'              => $dataProduct['ubicacion_id'],
            'presentacion_id'           => $dataProduct['presentacion_id'],
            'category_id'               => $dataProduct['category_id'],
            'subcategoria_id'           => $dataProduct['subcategoria_id'],
        ]);

        return $product;
    }

    private function guardarDatosInventario($datosInventario)
    {
        $inventario = Inventario::where('product_id', $datosInventario['product_id'])->first();

        if($datosInventario['cantidad_blister'] == null){
            $cantidad_blister = 0;
        }else{
            $cantidad_blister = $datosInventario['cantidad_blister'];
        }

        if($datosInventario['cantidad_unidad'] == null){
            $cantidad_unidad = 0;
        }else{
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

    public function reloadProductos()
    {
        $this->render();
    }

    public function render()
    {
        $productos = Product::search($this->buscar)
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

    public function destroy($id)
    {
        $orden      = OrdersDetails::where('product_id', $id)->first();
        $sales      = SaleDetail::where('product_id', $id)->first();
        $purchase   = PurchaseDetail::where('product_id', $id)->first();


        if ($purchase  or $orden or $sales) {
            session()->flash('warning', 'Producto esta siendo utilizado no se puede eliminar');
            $this->reloadProductos();
        } else {
            $product = Product::find($id);
            $product->delete();
            session()->flash('delete', 'Producto eliminado exitosamente');
            $this->reloadProductos();
        }
    }

    public function datomodal($product)
    {
        $this->emit('ProductstockEventEdit', $product);
    }




    //Metodos necesarios para la usabilidad


    public function updatingSearch(): void
    {
        $this->gotoPage(1);
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
}
