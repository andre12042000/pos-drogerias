<?php

namespace App\Http\Livewire\Abono;

use App\Models\Orders;
use App\Models\Purchase;
use Livewire\Component;
use App\Traits\AbonoCompra;
use Illuminate\Support\Facades\Auth;

class CompraAbonoComponent extends Component
{
    use AbonoCompra;
    public $tipo, $codigo, $total, $abonado, $saldo, $nuevo_saldo, $abonar, $selected_id, $metodo, $subtotal, $proximo_pago, $saldo_formateado;
    public $metodo_pago = 1;

    protected $listeners = ['CompraTransaccionEvent'];

    protected $rules = [
        'abonar'            =>  'required|integer|between:1000,100000000',
        'nuevo_saldo'       =>  'required|integer|between:0,100000000',
        'proximo_pago'      =>  'required',
    ];


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }



    public function CompraTransaccionEvent($transaccion, $type)
    {
            
        $this->saldo_formateado  = '$' . number_format($transaccion['saldo'], 0);;
        $this->tipo         = $type;
        $this->selected_id  = $transaccion['id'];
        $this->total        = '$' . number_format($transaccion['total'], 0);
        $this->saldo        = $transaccion['saldo'];
        $this->abonado      = '$' . number_format($transaccion['abono']);
        $this->metodo       = $transaccion['payment_method'];
         $this->proximo_pago = $transaccion['next_payment'];
    }

    public function render()
    {
        return view('livewire.abono.compra-abono-component');
    }

    public function updatedAbonar()
    {
        if($this->abonar > 0){
            $this->nuevo_saldo = $this->saldo - $this->abonar;

            if($this->nuevo_saldo < 0)
            {
                session()->flash('warning', 'Un saldo no puede ser negativo');
                return false;
            }
        }else{
            $this->nuevo_saldo = '';
        }
    
    }

    public function save()
    {
        if($this->nuevo_saldo < 0)
        {
            session()->flash('warning', 'El saldo no puede ser negativo');
            return false;
        }
        $validatedData = $this->validate();

        if($this->metodo == '1'){
            $type = 'App\Models\Purchase';
        }

        $detalle_abono = [
          
            'user_id'           => Auth::user()->id,
            'amount'            => $this->abonar,
            'payment_method'    => $this->metodo_pago,
            'abonableble_id'    => $this->selected_id,
            'abonable_type'     => $type,
        ];

        $this->AddAbonoCompra($detalle_abono);

        $this->descontarSaldo();

        $this->cancel();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');
        return redirect()->route('inventarios.purchase');


    }

  
    public function descontarSaldo()
    {
        $purchase = Purchase::findOrFail($this->selected_id);

        if($purchase){

            $saldo = $purchase->saldo - $this->abonar;
            $abono = $purchase->abono + $this->abonar;

            $purchase->update([
                    'abono'            => $abono,
                    'saldo'            => $saldo,
                    'next_payment'     => $this->proximo_pago,
            ]);

        }else{
            session()->flash('warning', 'Ocurrio un error, verifique la transacción y vuelva a intentar');
            return false;
        }
    }

    public function cleanData()
    {
        $this->cancel();
        $this->dispatchBrowserEvent('close-modal');
      
    }

    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }


}

   

