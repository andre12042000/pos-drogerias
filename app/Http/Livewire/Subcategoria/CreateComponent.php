<?php

namespace App\Http\Livewire\Subcategoria;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategoria;

class CreateComponent extends Component
{
    public $name, $selected_id, $categoria_id;
    public $status = 'ACTIVE';
    public $categories, $nuevacategoria;

    protected $listeners = ['SubEvent', 'CategoryEvent'];

    public function SubEvent($sub)
    {
        $this->selected_id          = $sub['id'];
        $this->name                 = $sub['name'];
        $this->categoria_id         = $sub['category_id'];

        $this->status               = $sub['status'];

    }
    protected $rules = [
        'name'                  =>  'required|min:4|max:254|unique:subcategorias,name',

    ];

    protected $messages = [
        'name.required'                 => 'Este campo es requerido',
        'name.min'                      => 'Este campo requiere al menos 4 caracteres',
        'name.max'                      => 'Este campo no puede superar los 254 caracteres',
        'name.unique'                   => 'Este nombre ya ha sido registrado',

    ];
    public function render()
    {
        $this->categories = Category::all();
        return view('livewire.subcategoria.create-component');
    }
    public function storeOrupdate()
    {
        if($this->selected_id > 0){
            $this->update();
        }else{
            $this->save();
        }
        $this->emit('reloadsub');
    }

    public function save()
    {

        $validatedData = $this->validate();


         Subcategoria::create([
            'name'                  => mb_strtoupper($this->name),
            'category_id'           => $this->categoria_id,
            'status'                => $this->status,
        ]);

        $this->cancel();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');
    }

    public function update()
    {
      $this->validate([
        'name'       =>  'required|min:4|max:254|unique:subcategorias,name,' . $this->selected_id,
      ]);

        $sub = Subcategoria::find($this->selected_id);

        $sub->update([
            'name'                  => mb_strtoupper($this->name),
            'category_id'           => $this->categoria_id,
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
    public function CategoryEvent($category)
    {
        $this->categories = Category::all();
        $this->categoria_id = $category['id'];
    }


    public function guardarCategoria()
    {
        $this->validate([
            'nuevacategoria' =>  'required|min:4|max:100|unique:categories,name',
        ],[
            'nuevacategoria.required'          => 'Este campo es requerido',
            'nuevacategoria.min'               => 'Este campo requiere al menos 4 carácteres',
            'nuevacategoria.max'               => 'Este campo no puede superar los 100 carácteres',
            'nuevacategoria.unique'            => 'Este categoría ya existe',
        ]);

        $category = Category::create([
            'name'  => strtolower($this->nuevacategoria),
        ]);

        $this->nuevacategoria = '';

        $this->emit('CategoryEvent', $category);
    }
}

