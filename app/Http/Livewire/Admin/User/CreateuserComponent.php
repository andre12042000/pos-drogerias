<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Exception;
use GuzzleHttp\Psr7\Request;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class CreateuserComponent extends Component
{
    public  $active = 'ACTIVO';
    public $number_document, $name, $photo, $email, $selected_id, $image;

    use WithFileUploads;
    public $rol_usuario = [];

    protected $listeners = ['userEvent'];

    public function userEvent($user)
    {
        $this->rol_usuario = [];
        $this->selected_id      = $user['id'];
        $this->name             = $user['name'];
        $this->active           = $user['status'];
        $this->email            = $user['email'];
        $this->image            = $user['photo'];

        if (count($user['roles']) > 0) {
            foreach ($user['roles'] as $rol) {
                $this->rol_usuario[] = +$rol['id'];
            }
        }
    }
    protected $rules = [
        'name'          => 'required|min:4|max:254|unique:users,name',
        'email'         => 'required|string|email|max:255|unique:users,email',
        'active'        => 'required',
        'photo'         => 'nullable|mimes:jpg,jpeg,bmp,png',
        'rol_usuario'   => 'required|array|min:1',
    ];

    protected $messages = [
        'name.required'     => 'El campo nombre es requerido',
        'name.min'          => 'El campo nombre debe tener al menos 6 caracteres',
        'name.max'          => 'El campo nombre no puede superar los 254 caracteres',
        'name.unique'       => 'El campo nombre ya se encuentra registrado',
        'active.required'   => 'Este campo es requerido',
        'email.max:'        => 'El campo email no puede tener mas de 255 caracteres',
        'email.email:'      => 'El campo correo electrónico no es una dirección válida',
        'email.unique'      => 'Este correo ya se encuentra registrado',
        'photo.mimes'       => 'El formato de imagen no es valido',
    ];
    public function render()
    {
        $roles = Role::all();

        return view('livewire.admin.user.create-user-component', compact('roles'));
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        try {

            $validatedData = $this->validate();

            if(!empty($this->photo)){
                $image = $this->photo->store('livewire-tem');
            }else{
                $image = null;
            }

            $user = User::create([
                'name'       => strtolower($this->name),
                'status'     => $this->active,
                'photo'      => $image,
                'email'      => $this->email,
                'password'   => bcrypt('123456789'),
            ]);

            $user->roles()->sync($this->rol_usuario);

            $this->cleanData();
            session()->flash('message', 'Usuario  creado exitosamente');
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }

    public function cleanData()
    {
        $this->reset();
        $this->resetErrorBag();
    }



    public function storeOrupdate()
    {
        if ($this->selected_id > 0) {
            $this->update();
        } else {
            $this->save();
        }
        $this->emit('reloadusuario');
    }


    public function update()
    {
        $this->validate([
            'name'          => 'required|min:4|max:254|unique:users,name,' . $this->selected_id,
            'email'         => 'required|string|email|max:255|unique:users,email,' . $this->selected_id,
            'active'        => 'required',
            'photo'         => 'nullable|mimes:jpg,jpeg,bmp,png',
            'rol_usuario'   => 'required|array|min:1',
        ]);

        $user = User::find($this->selected_id);

        $user->update([
            'name'       => strtolower($this->name),
            'status'     => $this->active,
            'email'      => $this->email,
        ]);


        $user->roles()->sync($this->rol_usuario);

        if(!empty($this->photo)){
            $image = $this->photo->store('livewire-tem');

            $user->update([
                'photo'      => $image,
            ]);

        }


        session()->flash('message', 'Usuario  actualizado exitosamente');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');
    }
}
