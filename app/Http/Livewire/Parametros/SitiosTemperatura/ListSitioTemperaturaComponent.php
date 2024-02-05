<?php

namespace App\Http\Livewire\Parametros\SitiosTemperatura;

use App\Models\SitesTemperature;
use Livewire\Component;

class ListSitioTemperaturaComponent extends Component
{

    protected $listeners = ['reloadDataSitesTemperatura'];

    function reloadDataSitesTemperatura()
    {
        $this->render();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('mostrar-registro-creado');

    }



    public function render()
    {
        $sitios = SitesTemperature::orderBy('name', 'asc')->get();

        return view('livewire.parametros.sitios-temperatura.list-sitio-temperatura-component', compact('sitios'))->extends('adminlte::page');
    }



}


