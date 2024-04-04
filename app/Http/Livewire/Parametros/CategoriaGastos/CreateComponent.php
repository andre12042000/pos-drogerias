<?php

namespace App\Http\Livewire\Parametros\CategoriaGastos;

use App\Models\CategoryGastos;
use Livewire\Component;

class CreateComponent extends Component
{
    public $name, $selected_id;
    public $status = 'ACTIVE';

    protected $listeners = ['GastoEvent'];

    public function GastoEvent($gastos)
    {
        $this->selected_id          = $gastos['id'];
        $this->name                 = $gastos['name'];
        $this->status               = $gastos['status'];

    }
    protected $rules = [
        'name'                  =>  'required|min:4|max:254|unique:category_gastos,name',

    ];

    protected $messages = [
        'name.required'                 => 'Este campo es requerido',
        'name.min'                      => 'Este campo requiere al menos 4 caracteres',
        'name.max'                      => 'Este campo no puede superar los 254 caracteres',
        'name.unique'                   => 'Este nombre ya ha sido registrado',

    ];

    public function render()
    {
        return view('livewire.parametros.categoria-gastos.create-component');
    }
    public function storeOrupdate()
    {
        if($this->selected_id > 0){
            $this->update();
        }else{
            $this->save();
        }
        $this->emit('reloadCategoriaGastos');
    }

    public function save()
    {

        $validatedData = $this->validate();


         CategoryGastos::create([
            'name'                  => mb_strtoupper($this->name),
            'status'                => $this->status,
        ]);

        $this->cancel();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert-create');
    }

    public function update()
    {
      $this->validate([
        'name'       =>  'required|min:4|max:254|unique:category_gastos,name,' . $this->selected_id,
      ]);

        $categoria = CategoryGastos::find($this->selected_id);

        $categoria->update([
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

