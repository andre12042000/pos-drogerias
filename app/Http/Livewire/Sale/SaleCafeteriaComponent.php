<?php

namespace App\Http\Livewire\Sale;

use App\Models\Client;
use App\Models\MetodoPago;
use Livewire\Component;
use Livewire\WithPagination;

class SaleCafeteriaComponent extends Component
{
    public $client_id, $client_name;
    public $tipo_operacion = 'VENTA';

    public $metodo_pago = 1;

    protected $listeners = ['ClientEvent'];

    public function ClientEvent($client)
    {
        $this->client_id    = $client['id'];
        $this->client_name  = ucwords($client['name']);

        if ($this->client_id) {
            self::consultarDeudaClient($this->client_id);
        }
    }

    function consultarDeudaClient($cliente_id)
    {
        $cliente = Client::findOrFail($cliente_id);

        if ($cliente->deuda > 0) {
            $this->dispatchBrowserEvent('notify_client_deuda', ['data' => $cliente]);
        }

        if($this->tipo_operacion == 'CRÉDITO'){
            $this->metodo_pago = 3;
        }
    }

    public function render()
    {

        $metodos_pago = MetodoPago::where('status', 'ACTIVE')->orderBy('id', 'desc')->get();

        return view('livewire.sale.sale-cafeteria-component', compact('metodos_pago'))->extends('adminlte::page');
    }





}
