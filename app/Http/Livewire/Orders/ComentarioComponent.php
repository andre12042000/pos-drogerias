<?php

namespace App\Http\Livewire\Orders;

use App\Models\OrderComentario;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ComentarioComponent extends Component
{
    public $comentario, $order, $status;

    public function Mount($order, $status)
    {
       
        $this->order = $order;
        $this->status = $status;
    }


    public function render()
    {
        $comentarios = OrderComentario::where('order_id', $this->order)->orderBy('created_at', 'DESC')->get();

        return view('livewire.orders.comentario-component', compact('comentarios'));
    }

    public function save()
    {
        $this->validate([
            'comentario'        => 'required|string|min:8|max:1000',
        ]);

        OrderComentario::create([
            'user_id'       => Auth::user()->id,
            'order_id'      => $this->order,
            'comentario'    => ucfirst($this->comentario),
        ]);

        session()->flash('message', 'Observación registrada correctamente.');
        $this->comentario = '';

        $this->render();
    }

    public function update($observacion)
    {
        $this->validate([
            'comentario'        => 'required|string|min:8|max:1000',
        ]);

        $observacion = OrderComentario::find($observacion);

        $observacion->update([
            'user_id'       => Auth::user()->id,
            'comentario'    => ucfirst($this->comentario),
        ]);
    }
}
