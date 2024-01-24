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



        foreach ($venta->saleDetails as $item) {
            $detalleVenta = $item;
            $producto = $item->product;
            $this->identificarMetodoDescontarProductos($producto, $detalleVenta);
        }
    }



    function identificarMetodoDescontarProductos($producto, $detalleVenta)
    {
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

        if ($disponibilidad['disponible_caja'] == '1' && $disponibilidad['disponible_blister'] == '1' && $disponibilidad['disponible_unidad'] == '1') {
            //Si el producto se puede vender por caja, por blister o por unidad
            $this->descuentosCajaBlisterUnidad($producto, $detalleVenta, $cantidades);
        } elseif ($disponibilidad['disponible_caja'] == '1' && $disponibilidad['disponible_blister'] == '1' && $disponibilidad['disponible_unidad'] == '0') {
            // Si el producto se puede vender  por caja y por blisters
            $this->descuentosCajaBlister($producto, $detalleVenta, $cantidades);
        } else {
            // si el producto se puede vender por caja solamente
            $this->descuentosCaja($producto, $detalleVenta);
        }
    }

    function descuentosCajaBlisterUnidad($producto, $detalleVenta, $cantidades)
    {

        $formaVenta = $detalleVenta->forma;


        switch ($formaVenta) {
            case 'disponible_caja':

                $descontar_caja = $detalleVenta->quantity;
                $descontar_blisters = $descontar_caja * $producto->contenido_interno_blister;
                $descontar_unidad = $descontar_caja * $producto->contenido_interno_unidad;
                break;

            case 'disponible_blister':

                $cantidadCajasActuales = $producto->inventario->cantidad_caja; // obtiene las cantidad de cajas que hay actualmente en el inventario
                $cantidadActualBlisters = $producto->inventario->cantidad_blister; //cantidad actual de blister en inventario
                $blisterPorCaja = $cantidades['contenido_interno_blister']; // identifica cuantos blister componen una caja
                $blisterComprados = $detalleVenta->quantity;  //Obtiene los blister que han sido comprados
                $cantidadCajasDespuesCompra = floor(($cantidadActualBlisters - $blisterComprados) / $blisterPorCaja); // Calcula la cantidad de cajas despues de la compra
                $descontar_caja = intval($cantidadCajasActuales - $cantidadCajasDespuesCompra); //Compara la cantidad si la cantidad de cajas es congruente con los blister
                $descontar_blisters = $detalleVenta->quantity;
                $unidadesPorBlister =  $cantidades['contenido_interno_unidad'] / $cantidades['contenido_interno_blister'];
                $descontar_unidad = $descontar_blisters * $unidadesPorBlister;

                break;
            case 'disponible_unidad':

                $cantidadCajasActuales = $producto->inventario->cantidad_caja; // obtiene las cantidad de cajas que hay actualmente en el inventario
                $cantidadUnidadesActuales = $producto->inventario->cantidad_unidad;
                $unidadesPorCaja = $cantidades['contenido_interno_unidad'];
                $unidadesCompradas = $detalleVenta->quantity;
                $cantidadCajasDespuesCompra = floor(($cantidadUnidadesActuales - $unidadesCompradas) / $unidadesPorCaja);
                $descontar_caja = intval($cantidadCajasActuales - $cantidadCajasDespuesCompra); //Compara la cantidad si la cantidad de cajas es congruente con los blister
                $unidadesPorBlister =  $cantidades['contenido_interno_unidad'] / $cantidades['contenido_interno_blister'];

                $descontar_blisters =  intval(round($unidadesCompradas / $unidadesPorBlister));
                $descontar_unidad = $detalleVenta->quantity;

                break;
        }

        $this->updateInventario($producto, $descontar_caja, $descontar_blisters, $descontar_unidad);
    }

    function descuentosCajaBlister($producto, $detalleVenta, $cantidades)
    {

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
            case 'disponible_unidad';

                $descontar_caja = 0;
                $descontar_blister = 0;
                $descontar_unidad = $detalleVenta->quantity;

                break;

        }

        $this->updateInventario($producto, $descontar_caja, $descontar_blister, $descontar_unidad);
    }



    function descuentosCaja($producto, $detalleVenta)
    {
        $forma = $detalleVenta->forma;
        $descontar_caja = $detalleVenta->quantity;

        $descontar_blister = 0;
        $descontar_unidad = 0;

        if ($forma == 'disponible_caja') {
            $this->updateInventario($producto, $descontar_caja, $descontar_blister, $descontar_unidad);
        } else {
            // Emitir una notificación para verificar los parametros y el inventario del producto
        }
    }

    function updateInventario($producto, $descontar_caja, $descontar_blister, $descontar_unidad)
    {
        // Obtener el producto del inventario
        $producto_inventario = Inventario::findOrFail($producto->id);

        // Actualizar stocks
        $nuevo_stock_caja = $this->calcularNuevoStock($producto_inventario->cantidad_caja, $descontar_caja);
        $nuevo_stock_blister = $this->calcularNuevoStock($producto_inventario->cantidad_blister, $descontar_blister);
        $nuevo_stock_unidad = $this->calcularNuevoStock($producto_inventario->cantidad_unidad, $descontar_unidad);



        // Actualizar el inventario
        $producto_inventario->update([
            'cantidad_caja'     => $nuevo_stock_caja,
            'cantidad_blister'  => $nuevo_stock_blister,
            'cantidad_unidad'   => $nuevo_stock_unidad,
        ]);

        if ($nuevo_stock_caja <= $producto->stock_min && $producto->stock_min > 0) {
            // Aquí puedes agregar la lógica para enviar una notificación
            // por ejemplo, enviar un correo electrónico, una alerta, etc.
            // Puedes utilizar las herramientas y métodos que prefieras para notificar.
            // Ejemplo:
          //  $this->enviarNotificacionStockBajo($producto, $nuevo_stock_caja, $nuevo_stock_blister, $nuevo_stock_unidad);
        }
    }

    private function calcularNuevoStock($stock_actual, $cantidad_descontar)
    {
        // Asegurarse de que el nuevo stock no sea negativo
        $nuevo_stock = max(0, $stock_actual - $cantidad_descontar);

        return $nuevo_stock;
    }



   /*  function updateInventario($producto, $descontar_caja, $descontar_blister, $descontar_unidad)
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
    } */
}