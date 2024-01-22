<?php

namespace App\Http\Livewire\Product;

use App\Models\Category;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Product;
use App\Models\Subcategoria;
use App\Models\Ubicacion;
use Livewire\Component;

class CreateComponent extends Component
{
    public $code, $name, $laboratorio_id, $ubicacion_id, $presentacion_id, $category_id, $subcategory_id;
    public $stock_min = 0;
    public $stock_max = 0;
    public $stock = 0;
    public $contenido_interno_blister, $contenido_interno_unidad;
    public $contenido_interno_caja = 1;
    public $disponible_caja = 1;
    public $disponible_blister = 0;
    public $disponible_unidad = 0;
    public $iva_product = 0;
    public $valor_iva_caja, $valor_iva_blister, $valor_iva_unidad;

    public $costo_caja, $costo_blister, $costo_unidad;
    public $precio_caja, $precio_blister, $precio_unidad;
    public $status = 'ACTIVE';

    public $laboratorios, $ubicaciones, $presentaciones, $categories,  $subcategorias;

    protected $listeners = ['guardarDatosEvent'];

    protected $rules = [
        'code'                      => 'required|min:4|max:50|unique:products,code',
        'name'                      => 'required|min:4|max:250',
        'laboratorio_id'            => 'nullable',
        'ubicacion_id'              => 'nullable',
        'presentacion_id'           => 'required',
        'category_id'               => 'nullable',
        'subcategory_id'            => 'nullable',
        'status'                    => 'required',
        'stock_min'                 => 'required',
        'stock_max'                 => 'required',
        'stock'                     => 'required',
        'iva_product'               => 'required',
        'disponible_caja'           => 'required',
        'disponible_blister'        => 'nullable',
        'disponible_unidad'         => 'nullable',
        'contenido_interno_caja'    => 'nullable',
        'contenido_interno_blister' => 'nullable',
        'contenido_interno_unidad'  => 'nullable',
        'costo_caja'                => 'required',
        'costo_blister'             => 'nullable',
        'costo_unidad'              => 'nullable',
        'precio_caja'               => 'nullable',
        'precio_blister'            => 'nullable',
        'precio_unidad'             => 'nullable',
        'valor_iva_caja'            => 'nullable',
        'valor_iva_blister'         => 'nullable',
        'valor_iva_unidad'          => 'nullable',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedDisponibleBlister($value)
    {
        if ($value == 1) {
            $this->rules['costo_blister'] = 'required';
            $this->rules['contenido_interno_blister'] = 'required';
            $this->rules['precio_blister'] = 'required';
        } else {
            $this->rules['costo_blister'] = 'nullable';
            $this->rules['contenido_interno_blister'] = 'nullable';
            $this->rules['precio_blister'] = 'nullable';
        }
    }

    public function updatedDisponibleUnidad($value)
    {
        if ($value == 1) {
            $this->rules['costo_unidad'] = 'required';
            $this->rules['contenido_interno_unidad'] = 'required';
            $this->rules['precio_blister'] = 'required';
        } else {
            $this->rules['costo_unidad'] = 'nullable';
            $this->rules['contenido_interno_unidad'] = 'nullable';
            $this->rules['precio_unidad'] = 'nullable';
        }
    }

    public function mount()
    {
        $this->obtenerLaboratorios();
        $this->obtenerUbicaciones();
        $this->obtenerPresentaciones();
        $this->obtenerPresentaciones();
        $this->obtenerCategorias();
        $this->obtenerSubCategorias();
    }

    public function cancel()
    {
       // $this->reset();
    }




    public function render()
    {
        return view('livewire.product.create-component');
    }

    /*---------------Metodos para obtener datos de los modelos --------------------*/

    function obtenerLaboratorios()
    {
        $this->laboratorios = Laboratorio::active()->get();
    }

    function obtenerUbicaciones()
    {
        $this->ubicaciones = Ubicacion::active()->get();
    }

    function obtenerPresentaciones()
    {
        $this->presentaciones = Presentacion::active()->get();
    }

    function obtenerCategorias()
    {
        $this->categories = Category::all();
    }

    function obtenerSubCategorias()
    {
        $this->subcategorias = Subcategoria::active()->get();
    }



    /*----------------Obtener datos calculados desde javascript  ----------*/

    function guardarDatosEvent($data)
    {
      //  dd($data);
        $this->costo_caja = isset($data['costo_caja']) ? (float) $data['costo_caja'] : 0;
        $this->iva_product = isset($data['iva_product']) ? (float) $data['iva_product'] : 0;
        $this->precio_caja = isset($data['precio_caja']) ? (float) $data['precio_caja'] : 0;
        $this->costo_blister = isset($data['costo_blister']) ? (float) $data['costo_blister'] : 0;
        $this->precio_blister = isset($data['precio_blister']) ? (float) $data['precio_blister'] : 0;
        $this->costo_unidad = isset($data['costo_unidad']) ? (float) $data['costo_unidad'] : 0;
        $this->precio_unidad = isset($data['precio_unidad']) ? (float) $data['precio_unidad'] : 0;
        $this->valor_iva_caja = isset($data['valor_iva_caja']) ? (float) $data['valor_iva_caja'] : 0;
        $this->valor_iva_blister = isset($data['valor_iva_blister']) ? (float) $data['valor_iva_blister'] : 0;
        $this->valor_iva_unidad = isset($data['valor_iva_unidad']) ? (float) $data['valor_iva_unidad'] : 0;


        $validatedData = $this->validate();

        $this->save($validatedData);

    }

    /*--------------- Guardado ---------------------*/

    function save($validatedData)
    {
        $nuevoProducto = Product::create($validatedData);

        $this->emit('reloadProductos');

    }


}
