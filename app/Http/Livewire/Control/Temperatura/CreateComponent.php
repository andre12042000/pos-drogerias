<?php

namespace App\Http\Livewire\Control\Temperatura;

use App\Models\SitesTemperature;
use App\Models\Temperature;
use Livewire\Component;

class CreateComponent extends Component
{
    protected $listeners = ['updateSitioId'];
    public $sitiosData = [];

    public function updateSitioId($index, $sitioId)
    {
        $this->sitiosData[$index]['sitio_id'] = $sitioId;
    }



    public function render()
    {
        $sitios = SitesTemperature::active()->get();

        return view('livewire.control.temperatura.create-component', compact('sitios'));
    }

    public function save()
    {
        $messages = [];

        foreach ($this->sitiosData as $index => $data) {
            $messages += [
                "sitiosData.{$index}.sitio_id.required" => 'El campo Sitio ID es requerido.',
                "sitiosData.{$index}.cadena_frio.required" => 'El campo Cadena Frio es requerido.',
                "sitiosData.{$index}.cadena_frio.numeric" => 'El campo Cadena Frio debe ser un número.',
                "sitiosData.{$index}.cadena_frio.between" => 'El campo Cadena Frio debe estar entre 0 y 99.',

                "sitiosData.{$index}.humedad.required" => 'El campo Humedad es requerido.',
                "sitiosData.{$index}.humedad.numeric" => 'El campo Humedad debe ser un número.',
                "sitiosData.{$index}.humedad.between" => 'El campo Humedad debe estar entre 0 y 99.',

                "sitiosData.{$index}.temperatura.required" => 'El campo Temperatura es requerido.',
                "sitiosData.{$index}.temperatura.numeric" => 'El campo Temperatura debe ser un número.',
                "sitiosData.{$index}.temperatura.between" => 'El campo Temperatura debe estar entre 0 y 99.',
            ];
        }

        $rules = [];

        foreach ($this->sitiosData as $index => $data) {
            $rules["sitiosData.{$index}.sitio_id"] = 'required';
            $rules["sitiosData.{$index}.cadena_frio"] = 'required|numeric|between:0,99';
            $rules["sitiosData.{$index}.humedad"] = 'required|numeric|between:0,99';
            $rules["sitiosData.{$index}.temperatura"] = 'required|numeric|between:0,99';
        }

    // Aplicar las reglas de validación
    $this->validate($rules, $messages);

        foreach($this->sitiosData as $data){
            Temperature::create([
                'sites_temperatures_id'     => $data['sitio_id'],
                'cadena_frio'               => $data['sitio_id'],
                'humedad'                   => $data['humedad'],
                'temperatura'               => $data['temperatura'],
                'fecha'                     => now()->toDateString(),
                'hora'                      => now()->toTimeString(),
            ]);
        }

        self::cleanData();

        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('registro-creado');

    }

    function cleanData()
    {
        $this->reset();
    }
}
