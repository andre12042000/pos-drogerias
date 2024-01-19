<?php

namespace App\Http\Livewire\Client;

use App\Models\Client;
use Livewire\Component;

class CreateComponent extends Component
{
    public $type_document, $number_document, $name, $phone, $address, $email, $selected_id;

    protected $listeners = ['clientEventEdit'];

    public function clientEventEdit($client)
    {

        $this->selected_id      = $client['id'];
        $this->name             = $client['name'];
        $this->phone            = $client['phone'];
        $this->address          = $client['address'];
        $this->email            = $client['email'];
        $this->type_document    = $client['type_document'];
        $this->number_document  = $client['number_document'];
    }

    protected $rules = [
        'name'      => 'required|min:4|max:254|unique:clients,name',
        'phone'     => 'nullable|min:8|max:20',
        'address'   => 'nullable|min:8|max:100',
        'email'     => 'nullable|string|email|max:255',
    ];

    protected $messages = [
        'name.required' => 'El campo nombre es requerido',
        'name.min'      => 'El campo nombre debe tener al menos 6 caracteres',
        'name.max'      => 'El campo nombre no puede superar los 254 caracteres',
        'name.unique'   => 'El campo nombre ya se encuentra registrado',
        'phone.min'     => 'El campo telefono debe tener al menos 8 dígitos',
        'phone.max'     => 'El campo telefono no puede tener mas de 20 dígitos',
        'address.min'   => 'El campo dirección debe tener al menos 8 caracteres',
        'address.max'   => 'El campo dirección no puede tener mas de 100 caracteres',
        'email.max'     => 'El campo email no puede tener mas de 255 caracteres',
        'email.email'   => 'El campo correo electrónico no es una dirección válida',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function render()
    {
        return view('livewire.client.create-component');
    }

    public function save()
    {
        $validatedData = $this->validate();

        $client = Client::create([
            'type_document'     => $this->type_document,
            'number_document'   => $this->number_document,
            'name'              => strtolower($this->name),
            'phone'             => $this->phone,
            'address'           => $this->address,
            'email'             => $this->email,
        ]);

        session()->flash('message', 'Cliente creado exitosamente');

        $this->emit('ClientEvent', $client);

        $this->dispatchBrowserEvent('close-modal');
        $this->cancel();

    }

    public function storeOrupdate()
    {
        if($this->selected_id > 0){
            $this->update();
            $this->emit('reloadClients', 'update');
        }else{
            $this->save();
            $this->emit('reloadClients', 'create');
        }

    }
    public function update()
    {
$this->validate(

    [   'name'      => 'required|min:4|max:254|unique:clients,name,' . $this->selected_id,
        'address'   => 'nullable|min:8|max:100',
        'phone'     => 'nullable|min:8|max:20',
        'email'     => 'nullable|string|email|max:255',
]
);
        $category = Client::find($this->selected_id);

        $category->update([
            'type_document'     => $this->type_document,
            'number_document'   => $this->number_document,
            'name'              => strtolower($this->name),
            'phone'             => $this->phone,
            'address'           => $this->address,
            'email'             => $this->email,
        ]);

        $this->cancel();
        //session()->flash('message', 'Cliente actualizado exitosamente');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');
    }


    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }
}
