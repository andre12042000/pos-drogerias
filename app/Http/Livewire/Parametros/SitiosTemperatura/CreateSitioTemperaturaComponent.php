<?php

namespace App\Http\Livewire\Parametros\SitiosTemperatura;

use App\Models\SitesTemperature;
use Livewire\Component;

class CreateSitioTemperaturaComponent extends Component
{
    public $name;
    public $status = 'ACTIVE';

    protected $rules = [
        'name'      => 'required|min:6|max:254|unique:sites_temperatures,name',
        'status'    => 'required',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected $messages = [
        'name.required' => 'El campo nombre es obligatorio.',
        'name.min' => 'El campo nombre debe tener al menos :min caracteres.',
        'name.max' => 'El campo nombre no puede tener más de :max caracteres.',
        'name.unique' => 'El nombre proporcionado ya existe en la base de datos.',
        'status.required' => 'El campo estado es obligatorio.',
    ];


    public function render()
    {
        return view('livewire.parametros.sitios-temperatura.create-sitio-temperatura-component');
    }

    public function save()
    {
        $validatedData = $this->validate();

        SitesTemperature::create($validatedData);

        $this->emit('reloadDataSitesTemperatura');
        $this->cleanData();
    }

    public function cleanData()
    {
        $this->reset();
    }
}
