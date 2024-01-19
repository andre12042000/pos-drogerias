<?php

namespace App\Http\Livewire\Service;

use Livewire\Component;
use App\Models\User;

class ListComponent extends Component
{
    public function render()
    {
        $users = User::active()->get();

        return view('livewire.service.list-component', compact('users'));
    }
}
