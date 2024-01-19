<?php

namespace App\Http\Livewire\Admin\Role;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateroleComponent extends Component
{
    public $name, $selected_id;
    public $permission_rol = [];
    protected $listeners = ['roleEvent'];

    public function roleEvent($rol)
    {
        $this->permission_rol = [];


        $this->selected_id      = $rol['id'];
        $this->name             = $rol['name'];

        if(count($rol['permissions']) > 0){
            foreach($rol['permissions'] as $permiso)
           {
               $this->permission_rol[] =+ $permiso['id'];
           }
       }

    }
    public function render()
    {
        $permissions = Permission::all();
        return view('livewire.admin.role.create-role-component', compact('permissions'));
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected $rules = [
        'name'      => 'required|min:4|max:254|unique:roles,name',
        'permission_rol' => 'required',
    ];

    protected $messages = [
        'name.required'             => 'El campo nombre es requerido',
        'name.min'                  => 'El campo nombre debe tener al menos 4 caracteres',
        'name.max'                  => 'El campo nombre no puede superar los 254 caracteres',
        'name.unique'               => 'Este nombre ya se encuentra registrado',
        'permission_rol.required'   => 'Debe seleccionar al menos un permiso',
    ];

    public function save()
    {
        $validatedData = $this->validate();

        $rol = Role::create([
            'name'       => strtolower($this->name)

        ]);

        $rol->permissions()->attach($this->permission_rol);

        $this->cleanData();
        session()->flash('message', 'Rol  creado exitosamente');
    }

    public function cleanData()
    {
            $this->reset();
            $this->resetErrorBag();

    }



    public function storeOrupdate()
    {
        if($this->selected_id > 0){
            $this->update();
        }else{
            $this->save();
        }
        $this->emit('reloadrol');
    }
    public function update()
    {
        $this->validate([
            'name'      => 'required|min:4|max:254|unique:roles,name,'. $this->selected_id,

        ]);
        $rol = Role::find($this->selected_id);

        $rol->update([
            'name'       => strtolower($this->name),

        ]);
        $rol->syncPermissions($this->permission_rol);
        $this->cleanData();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');
    }
}
