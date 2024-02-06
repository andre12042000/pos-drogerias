<?php

namespace App\Http\Livewire\Control\Temperatura;

use App\Models\Temperature;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 0;

    public $filtro_desde, $filtro_hasta;
    public $desde;
    public $hasta;

    public function mount()
    {
        // Inicializa las fechas con el día de hoy
        $this->filtro_desde = Carbon::today()->format('Y-m-d');
        $this->filtro_hasta = Carbon::today()->format('Y-m-d');
        $this->desde = Carbon::today()->format('Y-m-d');
        $this->hasta = Carbon::today()->format('Y-m-d');
    }


    public function render()
    {


        // Filtra los seguimientos por rango de fechas
        $seguimientos = Temperature::whereBetween('fecha', [$this->filtro_desde, $this->filtro_hasta])
            ->orderBy('id', 'desc')
            ->paginate($this->cantidad_registros);


        return view('livewire.control.temperatura.list-component', compact('seguimientos'))->extends('adminlte::page');
    }

    public function updatedDesde()
    {
        $this->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
        ]);


        self::filtrarData();


    }

    public function updatedHasta()
    {
        $this->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
        ]);


        self::filtrarData();
    }

    function filtrarData()
    {
        $this->filtro_desde = $this->desde;
        $this->filtro_hasta = $this->hasta;
    }
}
