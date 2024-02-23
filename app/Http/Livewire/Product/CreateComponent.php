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
    public $code, $name, $presentacion_id;
    public $category_id = 1;
    public $subcategory_id = 1;
    public $laboratorio_id = 1;
    public $ubicacion_id = 1;
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
        'laboratorio_id'            => 'required',
        'ubicacion_id'              => 'required',
        'presentacion_id'           => 'required',
        'category_id'               => 'required',
        'subcategory_id'            => 'required',
        'status'                    => 'required',
        'stock_min'                 => 'required',
        'stock_max'                 => 'required',
        'stock'                     => 'required',
        'iva_product'               => 'required',
        'disponible_caja'           => 'required',
        'disponible_blister'        => 'nullable',
        'disponible_unidad'         => 'nullable',
    ];

    protected $messages = [
        'required'                   => 'El campo :attribute es obligatorio.',
        'min'                        => 'El campo :attribute debe tener al menos :min caracteres.',
        'max'                        => 'El campo :attribute no debe exceder los :max caracteres.',
        'unique'                     => 'El campo :attribute ya está en uso.',
        'numeric'                    => 'El campo :attribute debe ser un número.',
        'required_if'                => 'El campo :attribute es obligatorio cuando :other es :value.',
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

        $rules = [
            'code'                      => 'required|min:4|max:50|unique:products,code',
            'name'                      => 'required|min:4|max:250',
            'laboratorio_id'            => 'required',
            'ubicacion_id'              => 'required',
            'presentacion_id'           => 'required',
            'category_id'               => 'required',
            'subcategory_id'            => 'required',
            'status'                    => 'required',
            'stock_min'                 => 'required|min:1',
            'stock_max'                 => 'required|min:1',
            'stock'                     => 'required',
            'iva_product'               => 'required',
            'disponible_caja'           => 'required',
            'disponible_blister'        => 'nullable',
            'disponible_unidad'         => 'nullable',
        ];


        if($this->disponible_blister > 0){
            $rules['contenido_interno_blister'] = ['required', 'numeric', 'min:1'];
            $rules['costo_blister'] = ['required', 'numeric', 'min:1'];
            $rules['precio_blister'] = ['required', 'numeric', 'min:1'];
        }

        if($this->disponible_unidad > 0){
            $rules['contenido_interno_unidad'] = ['required', 'numeric', 'min:1'];
            $rules['costo_unidad'] = ['required', 'numeric', 'min:1'];
            $rules['precio_unidad'] = ['required', 'numeric', 'min:1'];
        }




        $validatedData = $this->validate($rules);

        $this->save($validatedData);

    }

    /*--------------- Guardado ---------------------*/

    function save($validatedData)
    {
        $producto = Product::create($validatedData);

        $this->emit('reloadProductEvent', $producto);

        $this->dispatchBrowserEvent('close-modal');

    }

    function cleanData()
    {
        $this->code                      = '';
        $this->name                      = '';
        $this->laboratorio_id            = '';
        $this->ubicacion_id              = '';
        $this->presentacion_id           = '';
        $this->category_id               = '';
        $this->subcategory_id            = '';
        $this->status                    = '';
        $this->stock_min                 = '';
        $this->stock_max                 = '';
        $this->stock                     = '';
        $this->iva_product               = '';
        $this->disponible_caja           = '';
        $this->disponible_blister        = '';
        $this->disponible_unidad         = '';
        $this->contenido_interno_caja    = '';
        $this->contenido_interno_blister = '';
        $this->contenido_interno_unidad  = '';
        $this->costo_caja                = '';
        $this->costo_blister             = '';
        $this->costo_unidad              = '';
        $this->precio_caja               = '';
        $this->precio_blister            = '';
        $this->precio_unidad             = '';
        $this->valor_iva_caja            = '';
        $this->valor_iva_blister         = '';
        $this->valor_iva_unidad          = '';
    }




}
