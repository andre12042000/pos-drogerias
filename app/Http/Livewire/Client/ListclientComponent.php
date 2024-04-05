<?php

namespace App\Http\Livewire\Client;

use App\Models\Client;
use App\Models\Credit;
use App\Models\DetailOrder;
use App\Models\Sale;
use App\Models\ServiceOrder;
use Livewire\Component;
use Livewire\WithPagination;

class ListclientComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['reloadClients', 'redirigirEvent' => 'redirigir'];
    public $buscar, $search;
    public $cantidad_registros = 10;

    public function redirigir($cliente_id)
    {
        return redirect()->route('terceros.client.details', $cliente_id);
    }

    public function reloadClients($type)
    {
        if($type == 'create'){
            session()->flash('message', 'Cliente registrado correctamente');
        }
            $this->render();
    }

    public function render()
    {
        $clients = Client::search($this->search)
                        ->paginate($this->cantidad_registros);

        return view('livewire.client.list-client-component', compact('clients'));
    }

    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
    }

    public function sendData($client)
    {
        $this->emit('clientEventEdit', $client);
    }

    public function destroy($id)
    {
        $sales = Sale::where('client_id', $id)->first();
        $credits = Credit::where('client_id', $id)->first();
        $orden = ServiceOrder::where('client_id', $id)->first();

        if($credits  OR $orden OR $sales){
            session()->flash('warning', 'Cliente esta siendo utilizada no se puede eliminar');
            return view('livewire.client.list-client-component');
        }else{
            $client = Client::find($id);
            $client->delete();
            session()->flash('delete', 'Cliente  eliminado exitosamente');
            return view('terceros.client.index');

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
