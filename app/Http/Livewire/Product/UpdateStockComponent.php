<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;

class UpdateStockComponent extends Component
{

    public $selected_id, $contenido_interno_caja, $contenido_interno_blister, $contenido_interno_unidad, $costo_caja, $costo_blister  ;
    public $disponible_caja, $disponible_blister, $disponible_unidad;
    public $costo_unidad, $precio_caja, $precio_blister, $precio_unidad, $presentacion_id;

    protected $listeners = ['ProductstockEventEdit'];

    public function ProductstockEventEdit($producto)
    {
        $this->selected_id                  = $producto['id'];
        $this->contenido_interno_caja       = $producto['contenido_interno_caja'];
        $this->contenido_interno_blister    = $producto['contenido_interno_blister'];
        $this->contenido_interno_unidad     = $producto['contenido_interno_unidad'];

        $this->disponible_caja       = $producto['disponible_caja'];
        $this->disponible_blister    = $producto['disponible_blister'];
        $this->disponible_unidad     = $producto['disponible_unidad'];

        $this->costo_caja        = $producto['costo_caja'];
        $this->costo_blister     = $producto['costo_blister'];
        $this->costo_unidad      = $producto['costo_unidad'];

        $this->precio_caja        = $producto['precio_caja'];
        $this->precio_blister     = $producto['precio_blister'];
        $this->precio_unidad      = $producto['precio_unidad'];


        $this->presentacion_id     = $producto['presentacion_id'];
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


    public function render()
    {

        return view('livewire.product.update-stock-component');
    }

    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }

    public function actualizarstock(){


            $producto = Product::find($this->selected_id);

            $producto->update([
                'contenido_interno_caja'  => $this->contenido_interno_caja,
                'contenido_interno_blister'  => $this->contenido_interno_blister,
                'contenido_interno_unidad'  => $this->contenido_interno_unidad,

            ]);
            $this->cancel();
            $this->dispatchBrowserEvent('close-modal');
            $this->dispatchBrowserEvent('alert');
            $this->emit('reloadProductos');
        }


}
