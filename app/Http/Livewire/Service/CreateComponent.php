<?php

namespace App\Http\Livewire\Service;

use Livewire\Component;
use App\Models\User;

class CreateComponent extends Component
{
    protected $listeners = ['ClientEvent'];

    public function ClientEvent($client)
    {
        $this->client_id    = $client['id'];
        $this->client_name  = ucwords($client['name']);
    }


    public $client_name, $client_id;
    public function render()
    {
        $users = User::active()->get();
        return view('livewire.service.create-component', compact('users'));
    }
}
