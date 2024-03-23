<?php

namespace App\Http\Livewire\Empresa;

use App\Models\Empresa;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CreateComponente extends Component
{
    public $name, $image, $email, $telefono, $nit, $photo, $pre_orden, $pre_factu, $pre_servi, $pre_abono, $dv, $direccion, $modalaut;

    use WithFileUploads;

    public function mount()
    {
        $empresa = Empresa::find(1);
        $this->name         = $empresa->name;
        $this->nit          = $empresa->nit;
        $this->telefono     = $empresa->telefono;
        $this->email        = $empresa->email;
        $this->photo        = $empresa->image;
        $this->pre_factu    = $empresa->pre_facturacion;
        $this->pre_orden    = $empresa->pre_orden;
        $this->pre_servi    = $empresa->pre_servicio;
        $this->pre_abono    = $empresa->pre_abono;
        $this->dv           = $empresa->dv;
        $this->direccion    = $empresa->direccion;
        $this->modalaut     = $empresa->modal_aut_produc;


    }


    protected $rules = [
        'name'      => 'required|min:3|max:100',
        'nit'       => 'required|integer|digits_between:6,14',
        'telefono'  => 'required|integer|digits_between:6,14',
        'email'     => 'required|email|min:10|max:150',
        'image'     => 'nullable|image|max:2048',
        'pre_servi' => 'nullable|max:10',
        'pre_factu' => 'nullable|max:10',
        'pre_orden' => 'nullable|max:10',
        'pre_abono' => 'nullable|max:10',
        'dv'        => 'required|max:1',
    ];
     protected $messages = [
        'name.required'           => 'El campo nombre es requerido',
        'name.min'                => 'El campo nombre debe tener al menos 3 caracteres',
        'name.max'                => 'El campo nombre no puede superar los 100 caracteres',
        'phone.required'          => 'El campo telefono es requerido',
        'phone.digits_between'    => 'El campo telefono debe contener entre 6 y 14 dígitos.',
        'nit.required'            => 'El campo Nit es requerido',
        'nit.digits_between'      => 'El campo telefono debe contener entre 6 y 14 dígitos.',
        'email.max'               => 'El campo email no puede tener menos de 10 caracteres',
        'email.email'             => 'El correo electronico no es una dirección válida',
        'image.required'          => 'El campo logo es requerido',
        'image.image'             => 'El formato no es valido',
        'image.max'               => 'Supero el peso permitido 2MB',
        'pre_servi.max'           => 'El campo nombre no puede superar los 10 caracteres',
        'pre_factu.max'           => 'El campo nombre no puede superar los 10 caracteres',
        'pre_orden.max'           => 'El campo nombre no puede superar los 10 caracteres',

    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }



    public function render()
    {
        return view('livewire.empresa.create-componente');
    }

    public function update()
    {
        $validatedData = $this->validate();

        $empresa = Empresa::find(1);

        $empresa->update([
            'name'               => strtolower($this->name),
            'nit'                => $this->nit,
            'telefono'           => $this->telefono,
            'email'              => $this->email,
            'pre_facturacion'    => $this->pre_factu,
            'pre_orden'          => $this->pre_orden,
            'pre_servicio'       => $this->pre_servi,
            'pre_abono'          => $this->pre_abono,
            'dv'                 => $this->dv,
            'direccion'          => $this->direccion,
            'modal_aut_produc'   => $this->modalaut,

        ]);

        if($this->image)
        {
            $photo = $this->image->store('livewire-tem');
            $empresa->update([
                'image'     => $photo,
            ]);

        }


        return redirect()->route('empresas')->with('message', 'Empresa actualizada exitosamente');
    }

    public function cancel()
    {
             $this->reset();
            $this->resetErrorBag();
    }


}
