<?php

namespace App\Http\Livewire\Control\Vencimientos;

use Livewire\Component;

class ListComponent extends Component
{
    public function render()
    {
        return view('livewire.control.vencimientos.list-component')->extends('adminlte::page');
    }
}
