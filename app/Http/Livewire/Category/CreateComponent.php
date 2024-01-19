<?php

namespace App\Http\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class CreateComponent extends Component
{
    public $name, $selected_id;

    protected $listeners = ['categoryEvent'];

    public function categoryEvent($category)
    {
        $this->selected_id  = $category['id'];
        $this->name         = $category['name'];
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }



    public function render()
    {
        return view('livewire.category.create-component');
    }

    public function storeOrupdate()
    {
        if($this->selected_id > 0){
            $this->update();
        }else{
            $this->save();
        }
        $this->emit('reloadcategories');
    }

    public function save()
    {

        $validatedData = $this->validate();


        $category = Category::create([
            'name'  => strtolower($this->name),
        ]);

        $this->cancel();

        session()->flash('message', 'Categoría  creado exitosamente');
    }

    public function update()
    {
      $this->validate([
        'name'       =>  'required|min:4|max:254|unique:categories,name,' . $this->selected_id,
      ]);

        $category = Category::find($this->selected_id);

        $category->update([
            'name'  => strtolower($this->name),
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
