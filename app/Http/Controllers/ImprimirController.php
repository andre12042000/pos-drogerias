<?php

namespace App\Http\Controllers;

use App\Models\PagoCreditos;
use Illuminate\Http\Request;
USE App\Traits\ImprimirTrait;

class ImprimirController extends Controller
{
    use ImprimirTrait;
    public function imprimirPagoRecibo($pago_id)
    {
        try {
                $recibo =  PagoCreditos::findOrFail($pago_id);

                $reciboBody = [];

                // Sección 1
                $reciboBody[] = [
                    'label' => 'OPERACION',
                    'value' => 'PAGO VENTA CREDITO',
                ];

                $reciboBody[] = [
                    'label' => 'Recibo',
                    'value' => $recibo->full_nro,
                ];

                $reciboBody[] = [
                    'label' => 'Metodo de Pago',
                    'value' => $recibo->metodopago->name,
                ];

                $reciboBody[] = [
                    'label' => 'Fecha de pago',
                    'value' => $recibo->created_at->format('d-m-Y H:i'),
                ];

                $reciboBody[] = [
                    'label' => 'Detalles del pago',
                    'value' => '',
                ];

                $reciboBody[] = [
                    'label' => '------------------------',
                    'value' => '',
                ];

                //Relacion pagos

                foreach($recibo->pagocreditodetalles as $detalle){

                        $reciboBody[] = [
                            'label' => 'Compra',
                            'value' => $detalle->credito->sale->full_nro,
                        ];

                        $reciboBody[] = [
                            'label' => 'Saldo recibido',
                            'value' => '$ ' . number_format($detalle->saldo_recibido, 0),
                        ];

                        $reciboBody[] = [
                            'label' => 'Valor pagado',
                            'value' => '$ ' . number_format($detalle->valor_pagado, 0),
                        ];

                        $reciboBody[] = [
                            'label' => 'Saldo',
                            'value' => '$ ' . number_format($detalle->saldo_restante, 0),
                        ];

                        $reciboBody[] = [
                            'label' => '------------',
                            'value' => '------------',
                        ];

                }


                $reciboBody[] = [
                    'label' => 'TOTAL PAGADO',
                    'value' => '$ ' . number_format($recibo->valor, 0),
                ];


                $this->imprimirRecibo($reciboBody);

            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage();
            }

            return redirect()->back();



    }
}
