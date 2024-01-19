<?php

namespace App\Http\Livewire\Product\Acciones;

use App\Models\Product;
use Livewire\Component;
use App\Traits\UpdateProduct;

class AgregarComponent extends Component
{
    use UpdateProduct;
    public $cant_agregar, $producto, $name, $selected_id, $stock_actual, $nuevo_stock;
    PUBLIC $productsadd = [];

    protected $listeners = ['ProductEventEdit'];

    public function ProductEventEdit($producto)
    {
        $this->selected_id  = $producto['id'];
        $this->name         = $producto['name'];
        $this->stock_actual     = $producto['stock'];
        $this->producto     = $producto;
    }
    public function updatedCantAgregar()
    {

        $this->nuevo_stock = $this->stock_actual + $this->cant_agregar;
    }

    public function render()
    {
        return view('livewire.product.acciones.agregar-component');
    }


    public function agregar()
    {
        $this->productsadd[] = [
            'id'            => $this->producto['id'],
            'code'          => $this->producto['code'],
            'quantity'      => $this->cant_agregar,
        ];

        $this->addProduct($this->productsadd);

        session()->flash('message', 'cantidad añadida al inventario exitosamente');
        $this->emit('reloadProductos');
    }
    public function cancelar()
    {
            $this->reset();
            $this->resetErrorBag();
    }
}
