<?php

namespace App\Http\Livewire\Purchase;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Livewire\Component;
use Livewire\WithPagination;

class ListarComponent extends Component
{
    public $purchases, $buscar;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cantidad_registros = 10;
    public function mount($purchases)
    {
        $this->purchases = $purchases;
    }


    public function render()
    {
        $purchaes = Purchase::search($this->buscar)->orderBy('purchase_date', 'desc')->paginate($this->cantidad_registros);
        return view('livewire.purchase.listar-component', compact('purchaes'));
    }

    public function sendDataAbono($purchaes)
    {

        $type = 'Abono compra';

        $this->emit('CompraTransaccionEvent',  $purchaes, $type);
    }

    public function destroy($id){

        $products = PurchaseDetail::where('purchase_id', $id)->first();

        if ($products) {
            session()->flash('warning', 'Esta compra esta siendo utilizada no se puede eliminar');
            return false;
        } else {
        $brand = Purchase::find($id);
        $brand->delete();
        session()->flash('message', 'Compra eliminada exitosamente!');
        }
    }
}
