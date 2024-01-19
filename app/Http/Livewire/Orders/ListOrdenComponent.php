<?php

namespace App\Http\Livewire\Orders;

use App\Models\Client;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithPagination;

class ListOrdenComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['reloadOrder', 'destroy' , 'reloadOrdersEvent'];

    public $cantidad_registros = 15;
    public $buscar, $filter_estado, $filter_proveedor;
    public $filter_asignado = '';



    public function reloadOrdersEvent()
    {
        $this->render();
    }

    public function render()
    {

        $orders = Orders::with('asignado')->search($this->buscar)
                            ->estado($this->filter_estado)
                            ->asignado($this->filter_asignado)
                            ->paginate($this->cantidad_registros);

        $asignados = Orders::select('assigned')
                  ->whereNotNull('assigned')
                  ->distinct()
                  ->get();


        $providers = Client::orderBy('name', 'ASC')->get();


      /*   foreach($orders as $orden){

            dd($orden);
        } */

        return view('livewire.orders.list-orden-component', compact('orders', 'providers', 'asignados'));
    }

    public function sendDataAbono($order)
    {

        $type = 'Orden de trabajo';

        $this->emit('DataTransaccionEvent', $order, $type);
    }


    public function reloadBrands()
    {
        $this->render();
    }



    public function sendData($order)
    {
        $this->emit('orderEvent', $order);
    }

    public function destroy($id)
    {
        $orden = OrdersDetails::where('order_id', $id)->first();

        if ($orden) {
            session()->flash('warning', 'Esta orden esta siendo utilizada no se puede eliminar');
            return false;
        } else {
            $orders = Orders::find($id);
            $orders->delete();
            session()->flash('message', 'Orden  eliminada exitosamente');
            return true;
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
