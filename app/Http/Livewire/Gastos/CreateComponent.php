<?php

namespace App\Http\Livewire\Gastos;

use Livewire\Component;
use App\Models\CategoryGastos;
use App\Models\MetodoPago;

class CreateComponent extends Component
{
    public $fecha, $descripcion, $user_id, $category_gastos_id, $metodo_pago_id, $picture, $total;
    public $precio_detalles, $cantidad_detalles, $descripcion_detalles, $subtotal;
    public function render()
    {
        $categorias = CategoryGastos::where('status', 'ACTIVE')->get();
        $medios = MetodoPago::all();

        return view('livewire.gastos.create-component', compact('categorias', 'medios'));
    }
}
