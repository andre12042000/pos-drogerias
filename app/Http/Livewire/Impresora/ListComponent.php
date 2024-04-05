<?php

namespace App\Http\Livewire\Impresora;

use App\Models\Impresora;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    protected $listeners = ['reloadImpresora'];
    public $buscar, $search;

    public function reloadImpresora()
    {
        $this->render();
    }

    public function render()
    {
        $impresoras = Impresora::search($this->search)->orderBy('nombre', 'ASC')
        ->paginate($this->cantidad_registros);
        return view('livewire.impresora.list-component', compact('impresoras'));
    }

    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
    }
    public function sendData($category)
    {
        $this->emit('impresoraEvent', $category);
    }

    public function updatingSearch(): void
    {
        $this->gotoPage(1);
    }


    public function doAction($action)
    {
        $this->resetInput();
    }

    //método para reiniciar variables
    private function resetInput()
    {
        $this->reset();
    }
}
