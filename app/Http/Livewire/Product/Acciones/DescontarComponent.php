<?php

namespace App\Http\Livewire\Product\Acciones;

use App\Models\Product;
use Livewire\Component;
use App\Traits\UpdateProduct;

class DescontarComponent extends Component
{
    public $cant_descontar, $producto, $name, $selected_id, $nuevo_stock, $stock_actual;
    public $productdis = [];

    use UpdateProduct;
    protected $listeners = ['ProductEventEdit'];

    public function render()
    {
        return view('livewire.product.acciones.descontar-component');
    }



    public function ProductEventEdit($producto)
{
    $this->selected_id      = $producto['id'];
    $this->name             = $producto['name'];
    $this->stock_actual     = $producto['stock'];
    $this->producto         = $producto;
}

public function updatedCantDescontar()
{

    $this->nuevo_stock = $this->stock_actual - $this->cant_descontar;
}



    public function descontar()
    {

        if($this->stock_actual >= $this->cant_descontar){
            $this->productdis[] = [
            'id'            => $this->producto['id'],
            'code'          => $this->producto['code'],
            'quantity'      => $this->cant_descontar,
        ];

        foreach($this->productdis as $product)
        {
            $this->discountProduct($product);
        } session()->flash('message', 'cantidad descontada al inventario exitosamente');
        $this->emit('reloadProductos');

        }else{
            session()->flash('warning', 'Unidades insuficientes para el cambio ' );
            return false;
        }
        $this->emit('reloadProductos');

    }
    public function cancelar()
    {
            $this->reset();
            $this->resetErrorBag();
    }

}

