<?php

namespace App\Http\Livewire\Mantenimiento\Equipos;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Mantenimiento\Entities\Equipos;

class SearchComponent extends Component
{
    public $buscar;
    use WithPagination;
    public $cantidad_registros = 10;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['reloadEquipos'];

    public function render()
    {
        $equipos = Equipos::search($this->buscar)
        ->paginate($this->cantidad_registros);
        return view('livewire.mantenimiento.equipos.search-component', compact('equipos'));
    }

    public function reloadEquipos()
    {
        $this->render();
    }

    public function selectEquipo($equipo)
    {
        $this->emit('EquipoEvent', $equipo);
    }
    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }
}
