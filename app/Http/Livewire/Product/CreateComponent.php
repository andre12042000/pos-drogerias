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

class CreateComponent extends Component
{
    use WithFileUploads;
    public $code, $name, $presentacion_id, $image;
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
    public $data_direct;

    public $costo_caja, $costo_blister, $costo_unidad;
    public $precio_caja, $precio_blister, $precio_unidad;
    public $status = 'ACTIVE';
    public $is_materia_prima = 'no';

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
        'is_materia_prima'          => 'required',
        'image'                     => 'nullable',
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
        $this->data_direct = $data;
        $this->costo_caja = isset($data['costo_caja']) ? (float) $data['costo_caja'] : 0;
        $this->iva_product = isset($data['iva_product']) ? (float) $data['iva_product'] : 0;
        $this->precio_caja = isset($data['precio_caja']) ? (float) $data['precio_caja'] : 0;
        $this->precio_blister = isset($data['precio_blister']) ? (float) $data['precio_blister'] : 0;
        $this->costo_blister = isset($data['costo_blister']) ? (float) $data['costo_blister'] : 0;
        $this->costo_unidad = isset($data['costo_unidad']) ? (float) $data['costo_unidad'] : 0;
        $this->precio_unidad = isset($data['precio_unidad']) ? (float) $data['precio_unidad'] : 0;
        $this->disponible_blister = isset($data['disponible_blister']) ? (float) $data['disponible_blister'] : 0;
        $this->disponible_unidad = isset($data['disponible_unidad']) ? (float) $data['disponible_unidad'] : 0;

        $this->contenido_interno_blister = isset($data['contenido_interno_blister']) ? (float) $data['contenido_interno_blister'] : 0;
        $this->contenido_interno_unidad = isset($data['contenido_interno_unidad']) ? (float) $data['contenido_interno_unidad'] : 0;
        $this->contenido_interno_caja = isset($data['contenido_interno_caja']) ? (float) $data['contenido_interno_caja'] : 1;



        $this->valor_iva_caja = self::calcularIvaPrecioVenta($this->costo_caja, $this->iva_product);
        $this->valor_iva_blister = self::calcularIvaPrecioVenta($this->costo_blister, $this->iva_product);
        $this->valor_iva_unidad = self::calcularIvaPrecioVenta($this->costo_unidad, $this->iva_product);


        $rules = [
            'code'                      => 'required|min:4|max:50|unique:products,code',
            'name'                      => 'required|min:4|max:250',
            'laboratorio_id'            => 'required',
            'ubicacion_id'              => 'required',
            'presentacion_id'           => 'required',
            'category_id'               => 'required',
            'subcategory_id'            => 'required',
            'status'                    => 'required',
            'stock_min'                 => 'required|min:0',
            'stock_max'                 => 'required|min:0',
            'stock'                     => 'required',
            'iva_product'               => 'required',
            'disponible_caja'           => 'required',
            'disponible_blister'        => 'nullable',
            'disponible_unidad'         => 'nullable',
            'costo_caja'                => 'required',
            'precio_caja'               => 'required',
            'precio_blister'            => 'nullable',
            'precio_unidad'             => 'nullable',
            'valor_iva_caja'            => 'required',
            'valor_iva_blister'         => 'required',
            'valor_iva_unidad'          => 'required',
            'is_materia_prima'          => 'required',
            'image'                     => 'nullable',
        ];


        if($this->disponible_blister > 0){
            $rules['contenido_interno_blister'] = ['required', 'numeric', 'min:0'];
            $rules['costo_blister'] = ['required', 'numeric', 'min:0'];
            $rules['precio_blister'] = ['required', 'numeric', 'min:0'];
        }

        if($this->disponible_unidad > 0){
            $rules['contenido_interno_unidad'] = ['required', 'numeric', 'min:0'];
            $rules['costo_unidad'] = ['required', 'numeric', 'min:0'];
            $rules['precio_unidad'] = ['required', 'numeric', 'min:0'];
        }



        $validatedData = $this->validate($rules);

        $this->save($validatedData);

    }

    function calcularIvaPrecioVenta($precio_venta, $porcentajeIva)
    {

        $iva =  round($precio_venta - ($precio_venta / (1 + ($porcentajeIva / 100))));

        return $iva;
    }

    /*--------------- Guardado ---------------------*/

    function save($validatedData)
    {
        try {
            // Verificar si se ha subido una imagen
            if ($this->image instanceof \Illuminate\Http\UploadedFile) {
                // Guardar la imagen en el directorio temporal 'livewire-temp'
                $this->image = $this->image->store('livewire-temp');
            } else {
                // Si no se ha subido una imagen, establecer la variable como null
                $this->image = null;
            }

            // Aquí puedes añadir más lógica si es necesario
        } catch (\Exception $e) {
            // En caso de error, enviar un evento al navegador con el mensaje de error
            $this->dispatchBrowserEvent('swal_error', ['message' => $e->getMessage()]);
        }

        // Crear el producto en la base de datos
        $producto = Product::create([
            'code'                      => $this->code,
            'name'                      => $this->name,
            'status'                    => $this->status,
            'iva_product'               => $this->iva_product,
            'valor_iva_caja'            => 0,
            'valor_iva_blister'         => 0,
            'valor_iva_unidad'          => 0,
            'stock'                     => $this->stock,
            'stock_min'                 => $this->stock_min,
            'stock_max'                 => $this->stock_max,
            'image'                     => $this->image,
            'disponible_caja'           => 1,
            'disponible_blister'        => ($this->contenido_interno_blister !== null && $this->contenido_interno_blister > 0) ? 1 : 0,
            'disponible_unidad'         => ($this->contenido_interno_unidad !== null && $this->contenido_interno_unidad > 0) ? 1 : 0,
            'contenido_interno_caja'    => 1,
            'contenido_interno_blister' => ($this->contenido_interno_blister !== null && $this->contenido_interno_blister > 0) ? $this->contenido_interno_blister : 0,
            'contenido_interno_unidad'  => ($this->contenido_interno_unidad !== null && $this->contenido_interno_unidad > 0) ? $this->contenido_interno_unidad : 0,
            'costo_caja'                => $this->costo_caja,
            'costo_blister'             => ($this->costo_blister !== null && $this->costo_blister > 0) ? $this->costo_blister : 0,
            'costo_unidad'              => ($this->costo_unidad !== null && $this->costo_unidad > 0) ? $this->costo_unidad : 0,
            'precio_caja'               => $this->precio_caja,
            'precio_blister'            => ($this->precio_blister !== null && $this->precio_blister > 0) ? $this->precio_blister : 0,
            'precio_unidad'             => ($this->precio_unidad !== null && $this->precio_unidad > 0) ? $this->precio_unidad : 0,
            'medida_id'                 => null,
            'brand_id'                  => null,
            'exento'                    => 0,
            'excluido'                  => 0,
            'no_gravado'                => 0,
            'gravado'                   => 0,
        ]);

        // Enviar un evento al navegador indicando que el producto ha sido guardado
        $this->dispatchBrowserEvent('producto_guardado');

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
