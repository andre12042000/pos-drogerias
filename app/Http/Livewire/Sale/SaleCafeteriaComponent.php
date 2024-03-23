<?php

namespace App\Http\Livewire\Sale;


use Livewire\Component;
use Livewire\WithPagination;

class SaleCafeteriaComponent extends Component
{
    public function render()
    {


        return view('livewire.sale.sale-cafeteria-component')->extends('adminlte::page');
    }





}
