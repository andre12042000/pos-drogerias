<?php

namespace App\Http\Livewire\Control\Temperatura;

use App\Models\Temperature;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;


    public $desde;
    public $hasta;

    public function mount()
    {
        // Inicializa las fechas con el día de hoy
        $this->desde = Carbon::today()->format('Y-m-d');
        $this->hasta = Carbon::today()->format('Y-m-d');
    }


    public function render()
    {
        $this->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
        ]);

        // Filtra los seguimientos por rango de fechas
        $seguimientos = Temperature::whereBetween('fecha', [$this->desde, $this->hasta])
            ->orderBy('id', 'desc')
            ->paginate($this->cantidad_registros);


        return view('livewire.control.temperatura.list-component', compact('seguimientos'))->extends('adminlte::page');
    }
}
