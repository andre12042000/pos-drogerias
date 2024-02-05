<?php

namespace App\Http\Livewire\Vencimientos;

use App\Models\Vencimientos;
use Livewire\Component;

class ListComponent extends Component
{
    public $cantidad_registros = 10;
    public function render()
    {
        $vencimientos = Vencimientos::paginate($this->cantidad_registros);
        return view('livewire.vencimientos.list-component', compact('vencimientos'))->extends('adminlte::page');
    }
}
