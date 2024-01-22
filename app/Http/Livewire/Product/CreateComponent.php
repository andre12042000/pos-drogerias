<?php

namespace App\Http\Livewire\Product;

use App\Models\Category;
use App\Models\Laboratorio;
use App\Models\Presentacion;
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

    public $costo_caja, $costo_blister, $costo_unidad;
    public $precio_caja, $precio_blister, $precio_unidad;
    public $status = 'ACTIVE';

    public $laboratorios, $ubicaciones, $presentaciones, $categories,  $subcategorias;

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
}
