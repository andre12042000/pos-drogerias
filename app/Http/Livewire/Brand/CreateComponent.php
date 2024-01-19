<?php

namespace App\Http\Livewire\Brand;

use App\Models\Brand;

use Livewire\Component;

class CreateComponent extends Component
{
    public $name, $selected_id;

    protected $listeners = ['brandEvent'];

    public function brandEvent($brand)
    {
        $this->selected_id  = $brand['id'];
        $this->name         = $brand['name'];
    }

    protected $rules = [
        'name'       =>  'required|min:2|max:254|unique:brands,name',
    ];

    protected $messages = [
        'name.required' => 'Este campo es requerido',
        'name.min'      => 'Este campo requiere al menos 4 caracteres',
        'name.max'      => 'Este campo no puede superar los 254 caracteres',
        'name.unique'   => 'Esta marca ya ha sido registrada',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }




    public function render()
    {
        return view('livewire.brand.create-component');
    }

    public function save()
    {
        $validatedData = $this->validate();

        $brand = Brand::create([
            'name'  => strtolower($this->name),
        ]);

        $this->cancel();

        session()->flash('message', 'Marca creada exitosamente');
    }


    public function storeOrupdate()
    {
        if ($this->selected_id > 0) {
            $this->update();
        } else {
            $this->save();
        }
        $this->emit('reloadBrands');
    }


    public function update()
    {

        $this->validate(
            [
                'name'       =>  'required|min:2|max:254|unique:brands,name,' . $this->selected_id
            ]

        );
        $brand = Brand::find($this->selected_id);

        $brand->update([
            'name'  => strtolower($this->name),
        ]);

    $this->dispatchBrowserEvent('close-modal');

    $this->dispatchBrowserEvent('alert');
       
    }
   

    public function cancel()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
