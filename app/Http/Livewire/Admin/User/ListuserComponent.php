<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListuserComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    public  $buscar, $user, $filter_estado, $search;

    protected $listeners = ['reloadusuario'];


    public function reloadusuario()
    {
        $this->render();
    }



    public function render()
    {
        $users = User::with('roles')
                        ->orderBy('name', 'ASC')
                        ->search($this->search)
                        ->estado($this->filter_estado)
                        ->where('id', '>', '1')
                        ->paginate($this->cantidad_registros);

        return view('livewire.admin.user.list-user-component', compact('users'));
    }

    public function updatedBuscar()
    {
        $this->resetPage();

        $this->search = $this->buscar;
    }
    public function sendData($user)
    {
        $this->emit('userEvent', $user);
    }

    public function destroy($id)
    {
        $purchase = Purchase::where('user_id')->first();
        $sale = Sale::where('user_id')->first();
        $autenticated = Auth::user()->id;

        if($autenticated == $id)
        {
            session()->flash('warning', 'No es posible eliminar desde la misma cuenta autenticada');
            return false;
        }

        if ($purchase or $sale) {
            session()->flash('delete', 'No es posible eliminar este usuario, ya ha realizado transacciones');
            return false;
        } else {
            $user = User::find($id);
            $user->delete();
            session()->flash('message', 'Usuario eliminado correctamente');
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
