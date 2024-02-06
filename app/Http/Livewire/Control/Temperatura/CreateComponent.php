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
        $rules = [];
        foreach ($this->sitiosData as $index => $data) {
            $rules["sitiosData.{$index}.sitio_id"] = 'required';
            $rules["sitiosData.{$index}.cadena_frio"] = 'required';
            $rules["sitiosData.{$index}.humedad"] = 'required';
            $rules["sitiosData.{$index}.temperatura"] = 'required';
        }

    // Aplicar las reglas de validación
    $this->validate($rules);

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
