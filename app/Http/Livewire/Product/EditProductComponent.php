<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Ubicacion;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Subcategoria;
use Livewire\WithFileUploads;

class EditProductComponent extends Component
{
    use WithFileUploads;
    protected $listeners = ['editarProductEvent' => 'obtenerDetallesProducto'];

    public $categorias, $subcategorias, $ubicaciones, $presentaciones, $laboratorios, $is_materia_prima, $photo, $image;

    public $categoria_id, $ubicacion_id, $presentacion_id, $laboratorio_id;
    public $subcategoria_id;
    public $presentacion;
    public $estado_blister, $estado_unidad;
    public $disponible_blister_edit, $disponible_unidad_edit, $product_id;
    public $code_edit, $name_edit, $iva_edit, $stock_minimo_edit, $stock_maximo_edit, $blister_por_caja_edit, $unidad_por_caja_edit;
    public $CostoPorCajaEdit, $CostoPorBlisterEdit, $CostoPorUnidadEdit, $PrecioVentaCajaEdit, $PrecioVentaBlisterEdit, $PrecioVentaUnidadEdit, $status;
    public $existingImageUrl;


    public function Mount()
    {
        $this->categorias     =  Category::orderBy('name', 'ASC')->get();
        $this->subcategorias  =  Subcategoria::orderBy('name', 'ASC')->get();
        $this->ubicaciones    =  Ubicacion::orderBy('name', 'ASC')->get();
        $this->presentaciones  = Presentacion::orderBy('name', 'ASC')->get();
        $this->laboratorios  =  Laboratorio::orderBy('name', 'ASC')->get();
    }



    function obtenerPorcentajeGanancia($presentacion_id)
    {
        dd($presentacion_id);

    }

    public function obtenerDetallesProducto($product)
    {

        $this->product_id = $product['id'];
        $this->code_edit = $product['code'];
        $this->name_edit = $product['name'];
        $this->iva_edit = $product['iva_product'] ?? 0;
        $this->stock_minimo_edit = $product['stock_min'] ?? 0;
        $this->stock_maximo_edit = $product['stock_max'] ?? 0;
        $this->CostoPorCajaEdit = $product['costo_caja'] ?? 0;
        $this->PrecioVentaCajaEdit = $product['precio_caja'] ?? 0;
        $this->photo = $product['image'];
        $this->is_materia_prima = $product['is_materia_prima'];
        $this->blister_por_caja_edit = $product['contenido_interno_blister'] ?? 0;
        $this->CostoPorBlisterEdit = $product['costo_blister'] ?? 0;
        $this->PrecioVentaBlisterEdit = $product['precio_blister'] ?? 0;

        $this->unidad_por_caja_edit = $product['contenido_interno_unidad'] ?? 0;
        $this->CostoPorUnidadEdit = $product['costo_unidad'] ?? 0;
        $this->PrecioVentaUnidadEdit = $product['precio_unidad'] ?? 0;

        $this->categoria_id = $product['category_id'];
        $this->subcategoria_id = $product['subcategoria_id'] ?? 1;
        $this->ubicacion_id = $product['ubicacion_id'] ?? 1;
        $this->presentacion_id = $product['presentacion_id'] ?? 1;
        $this->laboratorio_id = $product['laboratorio_id'] ?? 1;
        $this->disponible_blister_edit = $product['disponible_blister'] ?? 0;
        $this->disponible_unidad_edit = $product['disponible_unidad'] ?? 0;
        $this->status = $product['status'];

        $this->existingImageUrl = $product['image'];

        self::estadosDisponibilidadBlister($this->disponible_blister_edit);
        self::estadosDisponibilidadUnidad($this->disponible_unidad_edit);
        self::obtenerPorcentajesGanancia($product['presentacion_id']);
    }

    public function updatedCostoPorCajaEdit($value){

        if($this->iva_edit > 0 && !is_null($this->iva_edit)){
            $iva = ($this->CostoPorCajaEdit * $this->iva_edit) / 100;
        }else{
            $iva = 0;
        }

        $ganancia = ($this->CostoPorCajaEdit * $this->presentacion->por_caja) / 100;

        $precio_venta_caja = round($value + $iva + $ganancia);


        $this->PrecioVentaCajaEdit = $precio_venta_caja;

        if($this->blister_por_caja_edit > 0 && !is_null($this->blister_por_caja_edit)){
           $this->CostoPorBlisterEdit =  round(($this->CostoPorCajaEdit + $iva) / $this->blister_por_caja_edit);
           $this->PrecioVentaBlisterEdit = round($this->PrecioVentaCajaEdit / $this->blister_por_caja_edit);
        }else{
            $this->CostoPorBlisterEdit = 0;
            $this->PrecioVentaBlisterEdit = 0;
        }

        if($this->unidad_por_caja_edit > 0 && !is_null($this->unidad_por_caja_edit)){
            $this->CostoPorUnidadEdit =  round(($this->CostoPorCajaEdit + $iva) / $this->unidad_por_caja_edit);
            $this->PrecioVentaUnidadEdit = round($this->PrecioVentaCajaEdit / $this->unidad_por_caja_edit);
         }else{
             $this->CostoPorUnidadEdit = 0;
             $this->PrecioVentaUnidadEdit = 0;

         }
    }



    function estadosDisponibilidadBlister($estado)
    {
        if ($estado > 0) {
            $this->estado_blister = '';
        } else {
            $this->estado_blister = 'disabled';
            $this->blister_por_caja_edit = 0;
            $this->CostoPorBlisterEdit = 0;
            $this->PrecioVentaBlisterEdit = 0;
        }
    }

    function estadosDisponibilidadUnidad($estado)
    {
        if ($estado > 0) {
            $this->estado_unidad = '';
        } else {
            $this->unidad_por_caja_edit = 0;
            $this->CostoPorUnidadEdit = 0;
            $this->PrecioVentaUnidadEdit = 0;

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
        if ($tipoproducto) {
            $this->presentacion = Presentacion::findOrFail($tipoproducto);
        } else {
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
            'CostoPorCajaEdit'              => 'required|min:0',
            'PrecioVentaCajaEdit'           => 'required|min:0',
            'is_materia_prima'          => 'required',
            'image'                     => 'nullable',
        ];

        if ($this->disponible_blister_edit == 1) {
            $rules['blister_por_caja_edit']     = ['required', 'min:1'];
            $rules['CostoPorBlisterEdit']       = ['required', 'min:1'];
            $rules['PrecioVentaBlisterEdit']    = ['required', 'min:1'];
        }

        if ($this->disponible_unidad_edit == 1) {
            $rules['unidad_por_caja_edit']      = ['required', 'min:1'];
            $rules['CostoPorUnidadEdit']        = ['required', 'min:1'];
            $rules['PrecioVentaUnidadEdit']     = ['required', 'min:1'];
        }

        $messages = [
            'code_edit.required'                    => 'El campo Código es obligatorio.',
            'code_edit.min'                         => 'El campo Código debe tener al menos :min caracteres.',
            'code_edit.max'                         => 'El campo Código no debe exceder los :max caracteres.',
            'code_edit.unique'                      => 'El Código ingresado ya está en uso. Por favor, elige otro.',

            'name_edit.required'                    => 'El campo Nombre es obligatorio.',
            'name_edit.min'                         => 'El campo Nombre debe tener al menos :min caracteres.',
            'name_edit.max'                         => 'El campo Nombre no debe exceder los :max caracteres.',

            'categoria_id.required'                 => 'Selecciona una categoría.',
            'subcategoria_id.required'              => 'Selecciona una subcategoría.',
            'ubicacion_id.required'                 => 'Selecciona una ubicación.',
            'presentacion_id.required'              => 'Selecciona una presentación.',
            'laboratorio_id.required'               => 'Selecciona un laboratorio.',
            'iva_edit.required'                     => 'El campo IVA es obligatorio.',

            'stock_minimo_edit.required'            => 'El campo Stock Mínimo es obligatorio.',
            'stock_maximo_edit.required'            => 'El campo Stock Máximo es obligatorio.',
            'disponible_blister_edit.required'      => 'Selecciona si el producto está disponible en blister o no.',
            'disponible_unidad_edit.required'       => 'Selecciona si el producto está disponible en unidad o no.',

            'CostoPorCajaEdit.required'             => 'El campo Costo por Caja es obligatorio.',
            'CostoPorCajaEdit.min'                  => 'El campo Costo por Caja debe ser al menos :min.',
            'PrecioVentaCajaEdit.required'          => 'El campo Precio de Venta por Caja es obligatorio.',
            'PrecioVentaCajaEdit.min'               => 'El campo Precio de Venta por Caja debe ser al menos :min.',
        ];

        if ($this->disponible_blister_edit == 1) {
            $messages += [
                'blister_por_caja_edit.required'     => 'El campo Blíster por Caja es obligatorio.',
                'blister_por_caja_edit.min'          => 'El campo Blíster por Caja debe ser al menos :min.',
                'CostoPorBlisterEdit.required'       => 'El campo Costo por Blíster es obligatorio.',
                'CostoPorBlisterEdit.min'            => 'El campo Costo por Blíster debe ser al menos :min.',
                'PrecioVentaBlisterEdit.required'    => 'El campo Precio de Venta por Blíster es obligatorio.',
                'PrecioVentaBlisterEdit.min'         => 'El campo Precio de Venta por Blíster debe ser al menos :min.',
            ];
        }

        if ($this->disponible_unidad_edit == 1) {
            $messages += [
                'unidad_por_caja_edit.required'      => 'El campo Unidad por Caja es obligatorio.',
                'unidad_por_caja_edit.min'           => 'El campo Unidad por Caja debe ser al menos :min.',
                'CostoPorUnidadEdit.required'        => 'El campo Costo por Unidad es obligatorio.',
                'CostoPorUnidadEdit.min'             => 'El campo Costo por Unidad debe ser al menos :min.',
                'PrecioVentaUnidadEdit.required'     => 'El campo Precio de Venta por Unidad es obligatorio.',
                'PrecioVentaUnidadEdit.min'          => 'El campo Precio de Venta por Unidad debe ser al menos :min.',
            ];
        }



        $this->validate($rules, $messages);

          try {

            $product = Product::findOrFail($this->product_id);

            $ivas = self::calcularIvas($this->CostoPorCajaEdit, $this->iva_edit);

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
                'is_materia_prima'          => $this->is_materia_prima,
            ]);

            if($this->image){

                if(isset($this->image) && $this->image instanceof \Illuminate\Http\UploadedFile){
                    $imagen = $this->image->store('livewire-tem');
                }

                $product->update([
                    'image'     => $imagen,
                ]);

            }

            return redirect()->route('inventarios.product')->with('success', 'Se ha actualizado correctamente el producto: ' . $product->name);

         } catch (\Exception $e) {

            $errorCode = $e->getMessage();

            $this->dispatchBrowserEvent('alert-error', ['errorCode' => $errorCode]);
        }
    }

    function calcularIvas($costo_caja, $porcentajeIva)
    {

        $data = [];
        if ($porcentajeIva > 0) {
            $iva_caja = ($costo_caja * $porcentajeIva) / 100;

            if ($this->blister_por_caja_edit > 0) {
                $precio_blister = $costo_caja / $this->blister_por_caja_edit;
                $iva_blister = round($precio_blister * ($porcentajeIva / 100), 0);
            } else {
                $iva_blister = 0;
             //   $precio_blister = 0;
            }

            if ($this->disponible_unidad_edit > 0) {
                $precio_unidad = $costo_caja / $this->unidad_por_caja_edit;
                $iva_unidad = round($precio_unidad * ($porcentajeIva / 100), 0);
            } else {
                $iva_unidad = 0;
              //  $precio_unidad = 0;
            }

            $data = [
                'iva_caja'      => $iva_caja,
                'iva_blister'   => $iva_blister,
                'iva_unidad'    => $iva_unidad,
            ];
        } else {
            $data = [
                'iva_caja'      => 0,
                'iva_blister'   => 0,
                'iva_unidad'    => 0,
            ];
        }


        return $data;
    }
}
