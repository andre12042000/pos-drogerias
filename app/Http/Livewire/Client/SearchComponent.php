<?php

namespace App\Http\Livewire\Client;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class SearchComponent extends Component
{
    public $buscar;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['reloadClients'];



    public function render()
    {
        $clients = Client::search($this->buscar)
                                 ->paginate('10');

        return view('livewire.client.search-component', compact('clients'));
    }

    public function reloadClients()
    {
        $this->render();
    }

    public function selectClient($client)
    {
        $this->emit('ClientEvent', $client);
    }
    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }
}
