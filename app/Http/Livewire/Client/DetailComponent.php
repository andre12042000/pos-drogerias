<?php

namespace App\Http\Livewire\Client;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Credit;
use App\Models\MetodoPago;
use App\Models\Orders;
use App\Models\PagoCreditos;
use App\Models\PagoCreditosDetalles;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Traits\ImprimirTrait;

class DetailComponent extends Component
{
    use WithPagination, ImprimirTrait;
    public $cliente;

    protected $listeners = ['realizarPagoTotalEvent'];

    public function mount($cliente_id)
    {
        $this->cliente = Client::findOrFail($cliente_id);
    }
    public function render()
    {
        $credits = Credit::where('client_id', $this->cliente->id)
                    ->with('sale')
                    ->orderBy('created_at', 'desc')
                    ->paginate(30);


        $metodos_pago = MetodoPago::where('status', 'ACTIVE')->get();

        $ventas = Sale::where('client_id', $this->cliente->id)
                        ->where('tipo_operacion', 'VENTA')
                        ->orderBy('created_at', 'desc')
                        ->paginate(30);

        $pagocreditos = PagoCreditos::where('client_id', $this->cliente->id)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(30);




        return view('livewire.client.detail-component', compact('credits', 'metodos_pago', 'ventas', 'pagocreditos'))->extends('adminlte::page');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /*----------------Metodos para realizar pago o abonos -----------------*/

    public function realizarPagoTotalEvent($data, $valorPagado, $metodoPago)
    {

        try {

            DB::transaction(function () use ($data, $valorPagado, $metodoPago) {

          // Ordena los elementos por fecha de creación
                usort($data, function($a, $b) {
                    return strtotime($a['createdAt']) - strtotime($b['createdAt']);
                });


                $dataRecibo = [
                    'metodo_pago_id'    => $metodoPago,
                    'valor'             => $valorPagado,
                    'client_id'         => $this->cliente->id,
                ];

                $recibo = self::crearPagoCredito($dataRecibo);

                // Inicializa el saldo restante con el valor pagado
                $saldoRestante = $valorPagado;

                foreach ($data as &$item) {
                    // Obtenemos el saldo actual
                    $saldoItem = floatval($item['saldo']);

                    // Calcula el monto a pagar para este item
                    $montoAPagar = min($saldoItem, $saldoRestante);


                    $credito = self::obtenerCredito($item['id']);


                    $saldo_recibido = $credito->saldo;
                    $newAbono = $credito->abono + $montoAPagar;
                    $newSaldo = $credito->saldo - $montoAPagar;

                    if($newSaldo == 0){
                        $estado = 0;
                    }else{
                        $estado = 1;
                    }

                    $credito->update([
                        'abono'     => $newAbono,
                        'saldo'     => $newSaldo,
                        'active'    => $estado,
                    ]);

                    $data_detalle = [
                        'recibo_id'         => $recibo->id,
                        'credit_id'         => $credito->id,
                        'saldo_recibido'    => $saldo_recibido,
                        'valor_pagado'      => $montoAPagar,
                        'saldo_restante'    => $saldo_recibido - $montoAPagar,
                    ];

                    self::RegistrarDetallePagoCredito($data_detalle);

                    // Actualiza el saldo del item
                    $item['saldo'] = $saldoItem - $montoAPagar;

                    // Actualiza el saldo restante
                    $saldoRestante -= $montoAPagar;

                    // Si ya no queda saldo restante, sal del bucle
                    if ($saldoRestante <= 0) {
                        break;
                    }
                }

                self::descontarDeudaCliente($valorPagado);
                self::crearRegistroCash($recibo);

                $this->dispatchBrowserEvent('pago_generado', ['pago' => $recibo->full_nro]);
            });
        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatchBrowserEvent('error_alert', ['error' => $e->getMessage()]);

            report($e);
        }

    }

    function crearPagoCredito($data)
    {
        $prefijo = 'RVC'; //Recibo venta crédito
        $numero =  self::obtenerUltimoNumero($prefijo);
        $credito = PagoCreditos::create([
            'prefijo'           => $prefijo,
            'nro'               => $numero,
            'full_nro'          => $prefijo . $numero,
            'metodo_pago_id'    => $data['metodo_pago_id'],
            'valor'             => $data['valor'],
            'client_id'         => $data['client_id'],
            'user_id'           => Auth::user()->id,
        ]);

        return $credito;

    }

    function crearRegistroCash($pago_credito)
    {
        Cash::create([
            'user_id'           => Auth::user()->id,
            'cashesable_id'     => $pago_credito['id'],
            'cashesable_type'   => 'App\Models\PagoCreditos',
            'quantity'          => $pago_credito['valor'],
        ]);

    }

    function obtenerUltimoNumero($prefijo)
    {
        $ultimoRecibo = PagoCreditos::where('prefijo', $prefijo)
                                    ->orderBy('nro', 'desc')
                                    ->first();

    if ($ultimoRecibo) {
        // Obtener el número actual y sumar 1
        $numero = (int) $ultimoRecibo->nro + 1;
    } else {
        // Si no hay registros previos, iniciar desde 1
        $numero = 1;
    }

    // Formatear el número con ceros a la izquierda (ej. 001, 002, ..., 199, 200, ...)
    $numeroFormateado = str_pad($numero, 3, '0', STR_PAD_LEFT);

    // Combinar el prefijo y el número formateado

    return $numeroFormateado;
    }

    function RegistrarDetallePagoCredito($data)
    {
        PagoCreditosDetalles::create([
            'recibo_id'         => $data['recibo_id'],
            'credit_id'         => $data['credit_id'],
            'saldo_recibido'    => $data['saldo_recibido'],
            'valor_pagado'      => $data['valor_pagado'],
            'saldo_restante'    => $data['saldo_restante'],
        ]);

        return true;

    }

    function  descontarDeudaCliente($valorPagado)
    {
        $newDeuda = $this->cliente->deuda - $valorPagado;

        $this->cliente->update([
            'deuda'     => $newDeuda,
        ]);

        return true;
    }

    function obtenerCredito($credito_id)
    {
        $credito = Credit::findOrFail($credito_id);

        return $credito;

    }


}
