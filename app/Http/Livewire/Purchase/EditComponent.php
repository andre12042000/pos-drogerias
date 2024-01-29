<?php

namespace App\Http\Livewire\Purchase;

use App\Models\PurchaseDetail;
use Livewire\Component;

class EditComponent extends Component
{
    public $purchase, $purchaseDetails;
    public $subtotal = 0;
    public $sumaiva = 0;
    public $sumadescuento = 0;
    public $totalfull = 0;

    protected $listeners = ['reloadProducts' => 'calcularValoresGlobales'];

    public function mount($purchase)
    {
        $this->purchase = $purchase;
        $this->calcularValoresGlobales();

    }

    public function render()
    {
        return view('livewire.purchase.edit-component');
    }

    public function enviarDataVentaModalAddProducto()
    {
        $this->emit('obtenerVentaEvent', $this->purchase);
    }

    function calcularValoresGlobales()
    {
        $this->subtotal = 0;
        $this->totalfull = 0;
        $this->sumaiva = 0;
        $this->sumadescuento = 0;



      //  $this->purchaseDetails = $this->purchase->purchaseDetails;

        $this->purchaseDetails = PurchaseDetail::where('purchase_id', $this->purchase->id)
                                    ->orderBy('esObsequio', 'asc')
                                    ->get();



        foreach ($this->purchaseDetails as $purchaseDetail) {

            $this->subtotal = $this->subtotal +  ($purchaseDetail->quantity * $purchaseDetail->purchase_price);
            $this->sumaiva += $purchaseDetail->mount_tax;
            $this->sumadescuento += $purchaseDetail->discount_tax;
        }

        $this->totalfull = $this->sumaiva + $this->subtotal - $this->sumadescuento;

    }

    public function destroy($item)
    {
        try {
            // Buscar el detalle de compra por ID
            $product = PurchaseDetail::findOrFail($item);

            // Realizar acciones adicionales antes de eliminar si es necesario
            // ...

            // Eliminar el detalle de compra
            $product->delete();

            // Puedes devolver una respuesta o redireccionar a la vista adecuada
            $this->emit('reloadProducts');
        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir durante la eliminación
            // Puedes personalizar el manejo de errores según tus necesidades
            session()->flash('error', 'Error al eliminar el producto: ' . $e->getMessage());

        }
    }

}
