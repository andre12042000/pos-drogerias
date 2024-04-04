<?php

namespace App\Http\Livewire\Impresora;

use App\Models\Impresora;
use Livewire\Component;

class CreateComponent extends Component
{
    public $name, $name_pc, $predeterminada;
    public  $selected_id;

    protected $listeners = ['impresoraEvent'];

    public function impresoraEvent($impresora)
    {
        $this->selected_id  = $impresora['id'];
        $this->name         = $impresora['nombre'];
        $this->name_pc         = $impresora['name_pc'];
        $this->predeterminada         = $impresora['predeterminada'];
    }

    protected $rules = [
        'name_pc'               =>  'required|min:4|max:254|unique:impresoras,name_pc',
        'name'                  =>  'required|min:4|max:254|unique:impresoras,nombre',
        'predeterminada'        =>  'required'
    ];

    protected $messages = [
        'name.required' => 'Este campo es requerido',
        'name.min'      => 'Este campo requiere al menos 4 caracteres',
        'name.max'      => 'Este campo no puede superar los 254 caracteres',
        'name_pc.unique'   => 'Este nombre ya ha sido registrado',
        'name_pc.required' => 'Este campo es requerido',
        'name_pc.min'      => 'Este campo requiere al menos 4 caracteres',
        'name_pc.max'      => 'Este campo no puede superar los 254 caracteres',
        'name_pc.unique'   => 'Este nombre ya ha sido registrado',
        'predeterminada.required' => 'Este campo es requerido',

    ];
    public function render()
    {
        return view('livewire.impresora.create-component');
    }


    public function storeOrupdate()
    {
        if($this->selected_id > 0){
            $this->update();
        }else{
            $this->save();
        }
        $this->emit('reloadImpresora');
    }

    public function save()
    {

        $validatedData = $this->validate();


        $impresora = Impresora::create([
            'name_pc'  => $this->name_pc,
            'nombre'  => strtolower($this->name),
            'predeterminada'  => strtolower($this->predeterminada),
        ]);

        $this->cancel();

        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert-create');
    }

    public function update()
    {
      $this->validate([
        'name_pc'               =>  'required|min:4|max:254|unique:impresoras,name_pc,' . $this->selected_id,
        'name'                  =>  'required|min:4|max:254|unique:impresoras,nombre,' . $this->selected_id,
        'predeterminada'       =>  'required',

      ]);

        $impresora = Impresora::find($this->selected_id);

        $impresora->update([
            'name_pc'  => strtolower($this->name_pc),
            'nombre'  => strtolower($this->name),
            'predeterminada'  => strtolower($this->predeterminada),

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
