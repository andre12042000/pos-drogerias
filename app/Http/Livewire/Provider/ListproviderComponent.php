<?php

namespace App\Http\Livewire\Provider;

use App\Models\Product;
use App\Models\Provider;
use App\Models\Purchase;
use Livewire\Component;
use Livewire\WithPagination;

class ListproviderComponent extends Component
{

    protected $listeners = ['reloadProvider'];
  use WithPagination;
  protected $paginationTheme = 'bootstrap';
  public $buscar, $search;
  public $cantidad_registros = 10;


  public function reloadProvider()
    {
        $this->render();
    }

    public function render()
    {
        $providers = Provider::search($this->search)->paginate($this->cantidad_registros);
        return view('livewire.provider.list-provider-component', compact('providers'));
    }
    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
    }

    public function sendData($provider)
    {
        $this->emit('providerEvent', $provider);
    }

    public function destroy($id)
    {
        $purchase = Purchase::where('provider_id', $id)->first();

        if($purchase){
            session()->flash('warning', 'Proveedor esta siendo utilizado no se puede eliminar');
            $this->reloadProvider();
        }else{
            $provider = Provider::find($id);
            $provider->delete();
            session()->flash('delete', 'Proveedor eliminado exitosamente');
            $this->reloadProvider();

        }

    }


    //Metodos necesarios para la usabilidad


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
