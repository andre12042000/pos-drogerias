<?php

namespace App\Listeners;

use App\Events\ConsumoInternoRealizado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\DescontarProductInventario;


class DescontarInventarioConsumoInterno
{
    use InteractsWithQueue, DescontarProductInventario;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ConsumoInternoRealizado $event)
    {

        $transaccion =  $event->transaccion;

        foreach($transaccion->detalles as $item){
            $detalleVenta = $item;
            $producto = $item->product;

            $this->descontar($producto, $detalleVenta);

        }
    }
}
