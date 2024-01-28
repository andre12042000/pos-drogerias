<?php

namespace App\Http\Livewire\Purchase;

use Livewire\Component;

class EditComponent extends Component
{
    public $purchase, $purchaseDetails;
    public $subtotal = 0;
    public $sumaiva = 0;
    public $sumadescuento = 0;
    public $totalfull = 0;

    public function mount($purchase)
    {
        $this->purchase = $purchase;
        $this->calcularValoresGlobales();

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

        $this->purchaseDetails = $this->purchase->purchaseDetails;

        foreach ($this->purchaseDetails as $purchaseDetail) {

            $this->subtotal = $this->subtotal +  ($purchaseDetail->quantity * $purchaseDetail->purchase_price);
            $this->sumaiva += $purchaseDetail->mount_tax;
            $this->sumadescuento += $purchaseDetail->discount_tax;
        }

        $this->totalfull = $this->sumaiva + $this->subtotal - $this->sumadescuento;

    }
    public function render()
    {
        return view('livewire.purchase.edit-component');
    }
}
