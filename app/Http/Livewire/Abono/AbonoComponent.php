<?php

namespace App\Http\Livewire\Abono;

use App\Models\Orders;
use Livewire\Component;
use App\Traits\RegistrarAbono;
use Illuminate\Support\Facades\Auth;

class AbonoComponent extends Component
{
    use RegistrarAbono;
    public $tipo, $codigo, $total, $abonado, $saldo, $abonar, $selected_id, $saldo_operacion, $client_id;
    public $metodo_pago = 1;
    public $nuevo_saldo = 0;

    protected $listeners = ['DataTransaccionEvent'];

    protected $rules = [
        'abonar'        =>  'required|integer|between:1000,100000000',
        'nuevo_saldo'   =>  'required|integer|between:0,100000000',
    ];


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }



    public function DataTransaccionEvent($transaccion, $type)
    {
        $this->tipo         = $type;
        $this->selected_id  = $transaccion['id'];
        $this->codigo       = $transaccion['full_nro'];
        $this->client_id    = $transaccion['client_id'];
        $this->total        = '$' . number_format($transaccion['valor'], 0);
        $this->abonado      = '$' . number_format($transaccion['abono'], 0);
        $this->saldo        = '$' . number_format($transaccion['saldo'], 0);
        $this->saldo_operacion = round($transaccion['saldo'], 2); // Redondea a 2 dígitos decimales

    }

    public function render()
    {
        return view('livewire.abono.abono-component');
    }

    public function updatedAbonar()
    {
        if($this->abonar > 0){
            $this->nuevo_saldo = $this->saldo_operacion - $this->abonar;

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
            session()->flash('warning', 'Hay saldo a favor del cliente...');
        }
        $validatedData = $this->validate();

        if($this->tipo == 'Orden de trabajo'){
            $type = 'App\Models\Orders';
        }

        $detalle_abono = [
            'full_nro'          => $this->codigo,
            'client_id'         => $this->client_id,
            'user_id'           => Auth::user()->id,
            'amount'            => $this->abonar,
            'payment_method'    => $this->metodo_pago,
            'abonableble_id'    => $this->selected_id,
            'abonable_type'     => $type,
        ];

        $this->AddAbono($detalle_abono);

        $this->descontarSaldo();

        $this->cancel();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');
        return redirect()->route('orders.index');


    }


    public function descontarSaldo()
    {
        $order = Orders::findOrFail($this->selected_id);

        if($order){

            $total_abono = $order->abono + $this->abonar;
            $saldo = $order->saldo - $this->abonar;

            $order->update([
                    'abono'     => $total_abono,
                    'saldo'     => $saldo,
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
