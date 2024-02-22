<?php

namespace App\Listeners;

use App\Events\VentaCreditoRealizada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\DescontarProductInventario;

class DescontarInventarioVentaCredito
{
    use DescontarProductInventario;
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
    public function handle(VentaCreditoRealizada $event)
    {
        $venta_credito = $event->venta_credito;

        foreach($venta_credito->details as $item){
            $detalleVenta = $item;
            $producto = $item->product;
            $this->descontar($producto, $detalleVenta);
        }

    }
}
