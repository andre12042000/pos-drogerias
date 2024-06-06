<?php

namespace App\Http\Livewire\Provider;

use App\Models\Provider;
use Livewire\Component;

class CreateComponent extends Component
{
    public $nit, $name, $phone, $address, $email, $selected_id;

    protected $listeners = ['providerEvent'];

    public function providerEvent($provider)
    {
        $this->selected_id   = $provider['id'];
        $this->name          = $provider['name'];
        $this->phone         = $provider['phone'];
        $this->nit           = $provider['nit'];
        $this->email         = $provider['email'];
        $this->address       = $provider['address'];

    }
    protected $rules = [
        'nit'       => 'nullable|unique:providers,nit',
        'name'      => 'required|min:3|max:254|unique:providers,name',
        'phone'     => 'nullable|integer|digits_between:6,14',
        'address'   => 'nullable|min:4|max:100',
        'email'     => 'nullable|string|email|max:255',
    ];

    protected $messages = [
        'nit.unique'                 => 'El campo nit ya se encuentra registrado',
        'name.required'              => 'El campo nombre es requerido',
        'name.min'                   => 'El campo nombre debe tener al menos 3 caracteres',
        'name.max'                   => 'El campo nombre no puede superar los 254 caracteres',
        'name.unique'                => 'El campo nombre ya se encuentra registrado',
        'phone.digits_between'       => 'El campo telefono debe tener al menos 6 y maximo 14 caracteres',
        'phone.integer'              => 'El campo telefono solo acepta numeros',
        'address.min:'               => 'El campo dirección debe tener al menos 4 caracteres',
        'address.max:'               => 'El campo dirección no puede tener mas de 100 caracteres',
        'email.max:'                 => 'El campo email no puede tener mas de 255 caracteres',
        'email.email:'               => 'El campo email no es una dirección válida',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function render()
    {
        return view('livewire.provider.create-component');
    }

    public function save()
    {
        $this->validate();

        $provider = Provider::create([
            'nit'       => $this->nit,
            'name'      => strtolower($this->name),
            'phone'     => $this->phone,
            'address'   => $this->address,
            'email'     => $this->email,
        ]);

        $this->cancel();

        session()->flash('message', 'Proveedor  creado exitosamente');

        $this->emit('ProviderEvent', $provider);
    }

    public function storeOrupdate()
    {
        if($this->selected_id > 0){
            $this->update();
        }else{
            $this->save();
        }
        $this->emit('reloadProvider');
    }

    public function update()
    {
        $this->validate(
            [
                'nit'       => 'nullable|unique:providers,nit,'. $this->selected_id,
                'name'      => 'required|min:3|max:254|unique:providers,name,'. $this->selected_id,
                'phone'     => 'nullable|integer|digits_between:6,14',
                'address'   => 'nullable|min:4|max:100',
                'email'     => 'nullable|string|email|max:255',
            ]);

        $provider = Provider::find($this->selected_id);

        $provider->update([
            'nit'       => $this->nit,
            'name'      => strtolower($this->name),
            'phone'     => $this->phone,
            'address'   => $this->address,
            'email'     => $this->email,
        ]);
        //session()->flash('message', 'Proveedor  actualizado exitosamente');
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
