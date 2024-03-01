<?php

namespace App\Http\Livewire\Product;

use App\Models\Category;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Product;
use App\Models\Subcategoria;
use App\Models\Ubicacion;
use Livewire\Component;

class EditProductComponent extends Component
{
    protected $listeners = ['editarProductEvent' => 'obtenerDetallesProducto'];

    public $categorias, $subcategorias, $ubicaciones, $presentaciones, $laboratorios;

    public $categoria_id, $subcategoria_id, $ubicacion_id, $presentacion_id, $laboratorio_id;
    public $presentacion;
    public $estado_blister, $estado_unidad;
    public $disponible_blister_edit, $disponible_unidad_edit, $product_id;
    public $code_edit, $name_edit, $iva_edit, $stock_minimo_edit, $stock_maximo_edit, $blister_por_caja_edit, $unidad_por_caja_edit;
    public $CostoPorCajaEdit, $CostoPorBlisterEdit, $CostoPorUnidadEdit, $PrecioVentaCajaEdit, $PrecioVentaBlisterEdit, $PrecioVentaUnidadEdit, $status;
    public function Mount()
    {
        $this->categorias     =  Category::orderBy('name', 'ASC')->get();
        $this->subcategorias  =  Subcategoria::orderBy('name', 'ASC')->get();
        $this->ubicaciones    =  Ubicacion::orderBy('name', 'ASC')->get();
        $this->presentaciones  = Presentacion::orderBy('name', 'ASC')->get();
        $this->laboratorios  =  Laboratorio::orderBy('name', 'ASC')->get();

    }

    public function obtenerDetallesProducto($product)
    {
        $this->product_id = $product['id'];
        $this->code_edit = $product['code'];
        $this->name_edit = $product['name'];
        $this->iva_edit = $product['iva_product'];
        $this->stock_minimo_edit = $product['stock_min'];
        $this->stock_maximo_edit = $product['stock_max'];
        $this->CostoPorCajaEdit = $product['costo_caja'];
        $this->PrecioVentaCajaEdit = $product['precio_caja'];

        $this->blister_por_caja_edit = $product['contenido_interno_blister'];
        $this->CostoPorBlisterEdit = $product['costo_blister'];
        $this->PrecioVentaBlisterEdit = $product['precio_blister'];

        $this->unidad_por_caja_edit = $product['contenido_interno_unidad'];
        $this->CostoPorUnidadEdit = $product['costo_unidad'];
        $this->PrecioVentaUnidadEdit = $product['precio_unidad'];

        $this->categoria_id = $product['category_id'];
        $this->subcategoria_id = $product['subcategoria_id'];
        $this->ubicacion_id = $product['ubicacion_id'];
        $this->presentacion_id = $product['presentacion_id'];
        $this->laboratorio_id = $product['laboratorio_id'];
        $this->disponible_blister_edit = $product['disponible_blister'];
        $this->disponible_unidad_edit = $product['disponible_unidad'];
        $this->status = $product['status'];
        self::estadosDisponibilidadBlister($this->disponible_blister_edit);
        self::estadosDisponibilidadUnidad($this->disponible_unidad_edit);
        self::obtenerPorcentajesGanancia($product['presentacion_id']);

    }

    function estadosDisponibilidadBlister($estado)
    {
        if($estado > 0){
            $this->estado_blister = '';
        }else{
            $this->estado_blister = 'disabled';
        }
    }

    function estadosDisponibilidadUnidad($estado)
    {
        if($estado > 0){
            $this->estado_unidad = '';
        }else{
            $this->estado_unidad = 'disabled';
        }
    }

    public function updatedDisponibleBlisterEdit($value)
    {
        self::estadosDisponibilidadBlister($value);
    }

    public function updatedDisponibleUnidadEdit($value)
    {
        self::estadosDisponibilidadUnidad($value);
    }

    function obtenerPorcentajesGanancia($tipoproducto)
    {
        if($tipoproducto){
            $this->presentacion = Presentacion::findOrFail($tipoproducto);
        }else{
            $this->presentacion = null;
        }

    }

    public function updatedPresentacionId($value)
    {
        self::obtenerPorcentajesGanancia($value);
    }

    public function render()
    {
        return view('livewire.product.edit-product-component');
    }

    public function actualizarProduct()
    {
        try {


        $rules = [
            'code_edit'                     => 'required|min:4|max:254|unique:products,code,' . $this->product_id,
            'name_edit'                     => 'required|min:4|max:254',
            'categoria_id'                  => 'required',
            'subcategoria_id'               => 'required',
            'ubicacion_id'                  => 'required',
            'presentacion_id'               => 'required',
            'laboratorio_id'                => 'required',
            'iva_edit'                      => 'required',
            'stock_minimo_edit'             => 'required',
            'stock_maximo_edit'             => 'required',
            'disponible_blister_edit'       => 'required',
            'disponible_unidad_edit'        => 'required',
        ];

        if ($this->disponible_blister_edit == 1) {
            $rules['blister_por_caja_edit']     = ['required', 'min:1'];
            $rules['CostoPorBlisterEdit']       = ['required', 'min:1'];
            $rules['PrecioVentaBlisterEdit']    = ['required', 'min:1'];
        }

        if ($this->disponible_unidad_edit == 1) {
            $rules['unidad_por_caja_edit']      = ['required', 'min:1'];
            $rules['CostoPorUnidadEdit']        = ['required', 'min:1'];
            $rules['CostoPorUnidadEdit']        = ['required', 'min:1'];
        }

        $this->validate($rules);

        $product = Product::findOrFail($this->product_id);

        $ivas = self::calcularIvas($this->PrecioVentaCajaEdit, $this->iva_edit);

        $product->update([
            'code'                      => $this->code_edit,
            'name'                      => $this->name_edit,
            'status'                    => $this->status,
            'iva_product'               => $this->iva_edit,
            'stock_min'                 => $this->stock_minimo_edit,
            'stock_max'                 => $this->stock_maximo_edit,
            'disponible_blister'        => $this->disponible_blister_edit,
            'disponible_unidad'         => $this->disponible_unidad_edit,
            'contenido_interno_blister' => $this->blister_por_caja_edit,
            'contenido_interno_unidad'  => $this->unidad_por_caja_edit,
            'costo_caja'                => $this->CostoPorCajaEdit,
            'costo_blister'             => $this->CostoPorBlisterEdit,
            'costo_unidad'              => $this->CostoPorUnidadEdit,
            'precio_caja'               => $this->PrecioVentaCajaEdit,
            'precio_blister'            => $this->PrecioVentaBlisterEdit,
            'precio_unidad'             => $this->PrecioVentaUnidadEdit,
            'laboratorio_id'            => $this->laboratorio_id,
            'ubicacion_id'              => $this->ubicacion_id,
            'presentacion_id'           => $this->presentacion_id,
            'category_id'               => $this->categoria_id,
            'subcategoria_id'           => $this->subcategoria_id,
            'valor_iva_caja'            => $ivas['iva_caja'],
            'valor_iva_blister'         => $ivas['iva_blister'],
            'valor_iva_unidad'          => $ivas['iva_unidad'],
        ]);

        return redirect()->route('inventarios.product')->with('success','Se ha actualizado correctamente el producto: ' . $product['name_edit']);

    } catch (\Exception $e) {

        $errorCode = $e->getCode();

        $this->dispatchBrowserEvent('alert-error', ['errorCode' => $errorCode]);
    }
    }

    function calcularIvas($precio_venta_caja, $porcentajeIva)
    {
        $data = [];
        if( $porcentajeIva > 0){
            $iva_caja = $precio_venta_caja * ($porcentajeIva / 100);

            if($this->blister_por_caja_edit > 0){
                $precio_blister = $precio_venta_caja / $this->blister_por_caja_edit;
                $iva_blister = $precio_blister * ($porcentajeIva / 100);
            }else{
                $iva_blister = 0;
            }

            if($this->disponible_unidad_edit > 0){
                $precio_unidad = $precio_venta_caja / $this->unidad_por_caja_edit;
            }else{
                $iva_unidad = 0;
            }

            $data = [
                'iva_caja'      => $iva_caja,
                'iva_blister'   => $iva_blister,
                'iva_unidad'    => $precio_unidad,
            ];


        }else{
            $data = [
                'iva_caja'      => 0,
                'iva_blister'   => 0,
                'iva_unidad'    => 0,
            ];

        }

        return $data;


    }
}
