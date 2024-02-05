<?php

namespace App\Http\Livewire\Control\Temperatura;

use Livewire\Component;

class ListComponent extends Component
{
    public function render()
    {
        return view('livewire.control.temperatura.list-component')->extends('adminlte::page');
    }
}
