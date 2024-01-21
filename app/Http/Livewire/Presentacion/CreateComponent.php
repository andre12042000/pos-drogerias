<?php

namespace App\Http\Livewire\Presentacion;

use Livewire\Component;

class CreateComponent extends Component
{
    public $name, $selected_id;
    public $disponible_caja, $disponible_blister, $disponible_unidad, $por_caja, $por_blister, $por_unidad;

    protected $listeners = ['presentacionEvent'];

    public function presentacionEvent($presentacion)
    {
        $this->selected_id  = $presentacion['id'];
        $this->name         = $presentacion['name'];
        $this->disponible_caja         = $presentacion['disponible_caja'];
        $this->disponible_blister         = $presentacion['disponible_blister'];
        $this->disponible_unidad         = $presentacion['disponible_unidad'];

        $this->por_caja         = $presentacion['por_caja'];
        $this->por_caja         = $presentacion['por_caja'];
        $this->por_unidad         = $presentacion['por_unidad'];

    }
    protected $rules = [
        'name'       =>  'required|min:4|max:254|unique:categories,name'
    ];

    protected $messages = [
        'name.required' => 'Este campo es requerido',
        'name.min'      => 'Este campo requiere al menos 4 caracteres',
        'name.max'      => 'Este campo no puede superar los 254 caracteres',
        'name.unique'   => 'Este nombre ya ha sido registrado',
    ];

    public function render()
    {
        return view('livewire.presentacion.create-component');
    }
}
