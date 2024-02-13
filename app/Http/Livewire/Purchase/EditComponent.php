<?php

namespace App\Http\Livewire\Purchase;

use App\Models\PurchaseDetail;
use Livewire\Component;
use App\Traits\AddProductsInventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class EditComponent extends Component
{
    use AddProductsInventario;

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

    public function confirmacionaplicar()
    {
     /*    $this->validate([
            'purchaseDetails' => 'required|array',
            'purchaseDetails.*' => 'required',
        ]);

        try { */

           // DB::transaction(function () {
                foreach ($this->purchaseDetails as $detalle) {
                    $this->addProducts($detalle);
                }

                $this->purchase->update([
                    'status' => 'APLICADO',
                ]);
         //   });

            $compra = $this->purchase->invoice . ' de ' . $this->purchase->provider->name;

            $this->dispatchBrowserEvent('compra-generada', ['compra' => $compra]);

       /*  } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];

            // Puedes manejar diferentes códigos de error aquí según tus necesidades
            if ($errorCode == 1062) {
                // Código de error específico (por ejemplo, duplicado de clave única)
                $this->dispatchBrowserEvent('error', ['error' => 'Duplicidad de clave única']);
            } else {
                // Otro código de error
                $this->dispatchBrowserEvent('error', ['error' => 'Error desconocido']);
            }

        } catch (\Exception $e) {
            // Manejo de otras excepciones generales
            $this->dispatchBrowserEvent('error', ['error' => 'Error desconocido']);
        } */
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
