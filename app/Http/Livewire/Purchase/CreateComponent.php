<?php

namespace App\Http\Livewire\Purchase;

use Livewire\Component;
use App\Models\Provider;
use App\Models\Purchase;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class CreateComponent extends Component
{
    use WithFileUploads;
    public $fecha, $proveedor, $factura_nro, $file, $picture, $photo, $proximo_pago, $abono_compra;
    public $porcentaje = 0;
    public $total_factura = 0;
    public $form_pago = 2;

    
    public function render()
    { 
        $providers = Provider::all();

        return view('livewire.purchase.create-component', compact('providers'));
    }

    public function save()
    {
        $this->validate([
            'fecha'         => 'required',
            'proveedor'     => 'required',
            'factura_nro'   => 'required',
            'porcentaje'    => 'required',
            'form_pago'     => 'required',
        ]);

        if($this->form_pago == 1){
            $this->validate([
                'abono_compra'     => 'required',
                'proximo_pago'     => 'required',
                
            ],[
                'abono_compra.required'=> 'Este campo es requerido',
                'proximo_pago.required'     => 'Este campo es requerido',
            ]);
            $saldo = $this->total_factura - $this->abono_compra;
        }else{
            $saldo = 0;
            $this->abono_compra = 0;
            $this->proximo_pago = null;

        }

        if($this->porcentaje > 0 && $this->total_factura > 0){
            $iva = ($this->porcentaje * $this->total_factura)/100;
        }else{
            $iva = 0;
        }

        if($this->picture){
            $photo = $this->picture->store('livewire-tem');
        }else{
            $photo = null;
        }
       

        $purchase = Purchase::create([
            'purchase_date'     => $this->fecha,
            'provider_id'       => $this->proveedor,
            'user_id'           => Auth::user()->id,
            'tax'               => $this->porcentaje,
            'mount_tax'         => $iva,
            'total'             => $this->total_factura,
            'invoice'           => $this->factura_nro,
            'picture'           => $photo,
            'payment_method'    => $this->form_pago,
            'abono'             => $this->abono_compra,
            'saldo'             => $saldo,
            'next_payment'      => $this->proximo_pago,
        ]);

        return redirect(route('inventarios.purchase.edit', $purchase));

    }
    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }
}
