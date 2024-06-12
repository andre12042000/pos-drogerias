<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Cotizacion;
use App\Models\PagoCreditos;
use Illuminate\Http\Request;
use App\Models\ConsumoInterno;
use App\Models\CotizacionDetalle;
use Illuminate\Support\Facades\Auth;
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
                            'value' =>  number_format($detalle->saldo_recibido, 0),
                        ];

                        $reciboBody[] = [
                            'label' => 'Valor pagado',
                            'value' =>  number_format($detalle->valor_pagado, 0),
                        ];

                        $reciboBody[] = [
                            'label' => 'Saldo',
                            'value' =>  number_format($detalle->saldo_restante, 0),
                        ];

                        $reciboBody[] = [
                            'label' => '------------',
                            'value' => '------------',
                        ];

                }


                $reciboBody[] = [
                    'label' => 'TOTAL PAGADO',
                    'value' =>  number_format($recibo->valor, 0),
                ];


                    $user_id = Auth::user()->id;
                    $cajero =  User::find($user_id);
                    $cajero = $cajero->name;



                        $this->imprimirRecibo($reciboBody, $cajero);

            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage();
            }

            return redirect()->back();



    }

    public function imprimirConsumoInterno($transaccion_id)
    {
        try {
            $recibo = ConsumoInterno::findOrFail($transaccion_id);

            $reciboBody = [];

            // Sección 1
            $reciboBody[] = [
                'label' => 'OPERACION',
                'value' => 'CONSUMO INTERNO',
            ];

            $reciboBody[] = [
                'label' => 'RECIBO',
                'value' => $recibo->full_nro,
            ];

            $reciboBody[] = [
                'label' => 'FECHA OPERACION',
                'value' => $recibo->created_at->format('d-m-Y H:i'),
            ];

            $reciboBody[] = [
                'label' => 'DETALLES',
                'value' => '',
            ];

            $reciboBody[] = [
                'label' => '------------------------',
                'value' => '',
            ];

            $reciboBody[] = [
                'label' => 'CANT. DESCRIPCION',
                'value' => '',
            ];

            //Relacion pagos

            foreach($recibo->detalles as $detalle){
                    $total = $detalle->quantity * $detalle->price;
                    $reciboBody[] = [
                        'label' => $detalle->quantity . ' ' . $detalle->product->name,
                        'value' =>  number_format($total, 0),
                    ];

            }

            $reciboBody[] = [
                'label' => '------------------------',
                'value' => '',
            ];


            $reciboBody[] = [
                'label' => 'TOTAL',
                 'value' =>  number_format($recibo->total, 0),
            ];





                    $user_id = Auth::user()->id;
                    $cajero =  User::find($user_id);
                    $cajero = $cajero->name;



                        $this->imprimirRecibo($reciboBody, $cajero);

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return redirect()->back();

    }



    public function cotizacionpdf($id)
    {

        $sales = Cotizacion::find($id);
        $empresa = Empresa::find(1);
        $detailsales = CotizacionDetalle::where('cotizacion_id', $id)->get();
        $dompdf = new Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('enable_html5_parser', true);
        $dompdf->set_option('enable_remote', true);

        // Cargar la vista que deseas convertir en PDF
        $view = view('ventas.pos.exportar', compact('sales', 'empresa', 'detailsales'))->render();

        // Renderizar la vista en un archivo PDF
        $dompdf->loadHtml($view);
        $dompdf->render();
        // Devolver el PDF como respuesta

        return $dompdf->stream('Cotización.pdf');

    }
}
