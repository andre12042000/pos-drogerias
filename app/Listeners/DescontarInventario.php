<?php

namespace App\Listeners;

use App\Events\VentaRealizada;
use App\Models\Combo;
use App\Models\Inventario;
use App\Models\Product;
use App\Models\User;
use App\Models\Vencimientos;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\LowStockNotification;
use App\Traits\DescontarProductInventario;

class DescontarInventario
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
     * @param  \App\Events\VentaRealizada  $event
     * @return void
     */
    public function handle(VentaRealizada $event)
    {
        $venta = $event->venta;

        foreach ($venta->saleDetails as $item) {

            $detalleVenta = $item;
            $producto = $item->product;



            if($producto->is_combo != '0'){

                $detalles_combo = self::obtenerDetallesCombo($producto['id']);

                for ($i = 1; $i <= $item->quantity; $i++) {
                    foreach($detalles_combo as $detalle){

                        $producto = self::obtenerProducto($detalle->product_id);

                        self::descontarInventario($producto, $detalle);
                    }
                }

            }else{

                    self::descontarInventario($producto, $detalleVenta);
            }

        }
    }

    function obtenerProducto($product_id)
    {
        $producto = Product::findOrFail($product_id);

        return $producto;
    }

    function obtenerDetallesCombo($combo_id)
    {
        $productos_combo = Combo::where('combo_id', $combo_id)->get();

        return $productos_combo;

    }

    function descontarInventario($producto, $detalleVenta)
    {
        $this->descontar($producto, $detalleVenta);
    }


}
