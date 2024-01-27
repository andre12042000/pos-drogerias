<?php

namespace App\Http\Livewire\Ubicacion;

use App\Models\Ubicacion;
use Livewire\Component;

class CreateComponent extends Component
{
    public $name, $selected_id;
    public $status = 'ACTIVE';

    protected $listeners = ['UbiciacionEvent'];

    public function UbiciacionEvent($presentacion)
    {
        $this->selected_id          = $presentacion['id'];
        $this->name                 = $presentacion['name'];
        $this->status               = $presentacion['status'];

    }
    protected $rules = [
        'name'                  =>  'required|min:4|max:254|unique:ubicacions,name',

    ];

    protected $messages = [
        'name.required'                 => 'Este campo es requerido',
        'name.min'                      => 'Este campo requiere al menos 4 caracteres',
        'name.max'                      => 'Este campo no puede superar los 254 caracteres',
        'name.unique'                   => 'Este nombre ya ha sido registrado',

    ];
    public function render()
    {
        return view('livewire.ubicacion.create-component');
    }

    public function storeOrupdate()
    {
        if($this->selected_id > 0){
            $this->update();
        }else{
            $this->save();
        }
        $this->emit('reloadubicacion');
    }

    public function save()
    {

        $validatedData = $this->validate();


         Ubicacion::create([
            'name'                  => mb_strtoupper($this->name),
            'status'                => $this->status,
        ]);

        $this->cancel();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');
    }

    public function update()
    {
      $this->validate([
        'name'       =>  'required|min:4|max:254|unique:ubicacions,name,' . $this->selected_id,
      ]);

        $presentacion = Ubicacion::find($this->selected_id);

        $presentacion->update([
            'name'                  => mb_strtoupper($this->name),
            'status'                => $this->status,
        ]);
        $this->cancel();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');
    }

    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }

}
