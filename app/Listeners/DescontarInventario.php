<?php

namespace App\Listeners;

use App\Events\VentaRealizada;
use App\Models\Inventario;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class DescontarInventario
{
    use InteractsWithQueue;
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



       foreach($venta->saleDetails as $item){
            $detalleVenta = $item;
            $producto = $item->product;
            $this->identificarMetodoDescontarProductos($producto, $detalleVenta);

       }



    }



    function identificarMetodoDescontarProductos($producto, $detalleVenta){
     //   dd($producto);

        // identificamos las variables para tomar la decisión como trabaja el producto son parametros
        $disponibilidad = [
            'disponible_caja' => $producto->disponible_caja,
            'disponible_blister' => $producto->disponible_blister,
            'disponible_unidad' => $producto->disponible_unidad,
        ];

        //cantidades por caja parametro de productos

        $cantidades = [
            'contenido_interno_caja' => $producto->contenido_interno_caja,
            'contenido_interno_blister' => $producto->contenido_interno_blister,
            'contenido_interno_unidad' => $producto->contenido_interno_unidad,
        ];

        if($disponibilidad['disponible_caja'] == '1' && $disponibilidad['disponible_blister'] == '1' && $disponibilidad['disponible_unidad'] == '1'){

            //Si el producto se puede vender por caja, por blister o por unidad

            dd('1');

        }elseif($disponibilidad['disponible_caja'] == '1' && $disponibilidad['disponible_blister'] == '1' && $disponibilidad['disponible_unidad'] == '0'){

            // Si el producto se puede vender  por caja y por blisters
            $this->descuentosCajaBlister($producto, $detalleVenta, $cantidades);

        }else{

            // si el producto se puede vender por caja solamente
            $this->descuentosCaja($producto, $detalleVenta);


        }






    }

    function descuentosCajaBlister($producto, $detalleVenta, $cantidades){

        $formaVenta = $detalleVenta->forma;

        $cantidades = [
            'contenido_interno_caja' => $producto->contenido_interno_caja,
            'contenido_interno_blister' => $producto->contenido_interno_blister,
            'contenido_interno_unidad' => $producto->contenido_interno_unidad,
        ];


        switch ($formaVenta) {
            case 'disponible_caja':

                $descontar_caja = $detalleVenta->quantity;
                $descontar_blister = $descontar_caja * $cantidades['contenido_interno_blister'];
                $descontar_unidad = 0;

                $this->updateInventario($producto, $descontar_caja, $descontar_blister, $descontar_unidad);
                break;
            case 'disponible_blister':

                $cantidadCajasActuales = $producto->inventario->cantidad_caja;
                $cantidadActualBlisters = $producto->inventario->cantidad_blister;
                $blisterPorCaja = $cantidades['contenido_interno_blister'];
                $blisterComprados = $detalleVenta->quantity;



                $cantidadCajasDespuesCompra = round($cantidadActualBlisters - $blisterComprados) / $blisterPorCaja;

                $descontar_caja_round = round($cantidadCajasActuales - $cantidadCajasDespuesCompra, 0);
                $descontar_caja = intval($descontar_caja_round);
                $descontar_unidad = 0;


                $this->updateInventario($producto, $descontar_caja, $blisterComprados, $descontar_unidad);
                break;
            default:

                break;
            // Agrega más casos según tus formas de venta si es necesario
        }


    }



    function descuentosCaja($producto, $detalleVenta)
    {
        $forma = $detalleVenta->forma;
        $descontar_caja = $detalleVenta->quantity;

        $descontar_blister = 0;
        $descontar_unidad = 0;

        if($forma == 'disponible_caja'){
            $this->updateInventario($producto, $descontar_caja, $descontar_blister, $descontar_unidad);
        }else{
            // Emitir una notificación para verificar los parametros y el inventario del producto
        }


    }



    function updateInventario($producto, $descontar_caja, $descontar_blister, $descontar_unidad)
    {
        $producto_inventario = Inventario::findorfail($producto->id);

        $stock_actual_caja = $producto_inventario->cantidad_caja;
        $stock_actual_blister = $producto_inventario->cantidad_blister;
        $stock_actual_unidad = $producto_inventario->cantidad_unidad;

        $nuevo_stock_caja = $stock_actual_caja - $descontar_caja;
        $nuevo_stock_blister = $stock_actual_blister - $descontar_blister;
        $nuevo_stock_unidad = $stock_actual_unidad - $descontar_unidad;

        $producto_inventario->update([
            'cantidad_caja'     => $nuevo_stock_caja,
            'cantidad_blister'  => $nuevo_stock_blister,
            'cantidad_unidad'   => $nuevo_stock_unidad,
        ]);
    }
}
