<?php

namespace App\Http\Livewire\Orders;

use App\Models\Orders;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Traits\HistorialAsignado;


class UpdateAsigComponent extends Component
{
    use HistorialAsignado;
    public $assigned, $selected_id, $order;
    protected $listeners = ['UpdateEvent'];

    public function UpdateEvent($orden)
    {

        $this->selected_id = $orden['id'];
        $this->order = Orders::find( $this->selected_id);
    }
    public function render()
    {
        $tecnicoRole = Role::where('name', 'Técnico')->first();

        if ($tecnicoRole) {
            // Obtener los usuarios que tienen el rol 'tecnico'
            $usuariosTecnicos = $tecnicoRole->users()->get();
            // $usuariosTecnicos = User::role('tecnico')->get();
        }
        if (count($usuariosTecnicos) < 1) {
            session()->flash('warning', 'No hay técnicos registrados en el sistema');
        }

        return view('livewire.orders.update-asig-component', compact('usuariosTecnicos'));
    }

    public function update() {

        $this->validate(
            [
                'assigned'       =>  'required'
            ],[
                'assigned.required' => 'Este campo es obligatorio'
            ]

        );
        $brand = Orders::find($this->selected_id);

        $brand->update([
            'assigned'  => $this->assigned,
        ]);
        $this->CrearHistorial($this->selected_id, $this->assigned);

        session()->flash('message', 'Se asigno la orden correctamente!');
        return redirect()->route('orders.edit', $this->order);
        $this->cancel();
    }


    public function cancel()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
