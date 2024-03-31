<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\Vencimientos;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventario;
use App\Notifications\LowStockNotification;
use App\Models\User;

trait DescontarProductInventario
{
    public function descontar($producto, $detalleVenta)
    {
        self::identificarMetodoDescontarProductos($producto, $detalleVenta);
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
        } elseif ($disponibilidad['disponible_caja'] == '1' && $disponibilidad['disponible_blister'] == '0' && $disponibilidad['disponible_unidad'] == '1') {
            $this->descuentosCajaUnidad($producto, $detalleVenta, $cantidades);
        } else {
            // si el producto se puede vender por caja solamente
            $this->descuentosCaja($producto, $detalleVenta);
        }
    }

    function descuentosCajaUnidad($producto, $detalleVenta, $cantidades){

        $formaVenta = $detalleVenta->forma;

        switch ($formaVenta) {
            case 'disponible_caja';
                $descontar_caja = $detalleVenta->quantity;
                $descontar_blisters = 0;
                $unidadesPorCaja = $cantidades['contenido_interno_unidad'];
                $descontar_unidad = $descontar_caja * $unidadesPorCaja;
                break;
            case 'disponible_unidad';
                $descontar_unidad = $detalleVenta->quantity;
                $unidadesPorCaja = $cantidades['contenido_interno_unidad'];

                $cantidadCajasActuales = $producto->inventario->cantidad_caja; // obtiene las cantidad de cajas que hay actualmente en el inventario
                $cantidadActualUnidades = $producto->inventario->cantidad_unidad; //cantidad actual de unidades en inventario

                $cantidadCajasDespuesCompra = intval(floor(($cantidadActualUnidades - $descontar_unidad) / $unidadesPorCaja)); // Calcula la cantidad de cajas despues de la compra
                $descontar_caja = intval($cantidadCajasActuales - $cantidadCajasDespuesCompra); //Compara la cantidad si la cantidad de cajas es congruente con los blister
                $descontar_blisters = 0;
                break;
        }

        $this->updateInventario($producto, $descontar_caja, $descontar_blisters, $descontar_unidad);

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

                $cantidadCajasDespuesCompra = intval(floor(($cantidadActualBlisters - $blisterComprados) / $blisterPorCaja)); //redondeamos hacia abajo pues si se descompleta la caja
                $descontar_caja = intval(round($cantidadCajasActuales - $cantidadCajasDespuesCompra)); // Cantidad de cajas que debemos descontar
                $descontar_blister = $detalleVenta->quantity;
                $descontar_unidad = 0;


              //  $this->updateInventario($producto, $descontar_caja, $blisterComprados, $descontar_unidad);
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
        $producto_inventario = Inventario::where('product_id', $producto->id)->first();


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
            $this->sendNotifyLowStock($producto);
        }

        if($descontar_caja > 0){
            $this->descontarItemVencimiento($producto->id, $descontar_caja);
        }
    }

    public function sendNotifyLowStock($product)
    {
        User::role(['Administrador'])->where('status', 'ACTIVO')
        ->each(function(User $user) use ($product){
           $user->notify(new LowStockNotification($product));
        });
    }

    private function calcularNuevoStock($stock_actual, $cantidad_descontar)
    {

        // Asegurarse de que el nuevo stock no sea negativo
        $nuevo_stock = max(0, $stock_actual - $cantidad_descontar);

        return $nuevo_stock;
    }

    function descontarItemVencimiento($product_id, $cantidad_descontar)
    {

        $vencimiento =  Vencimientos::where('product_id', $product_id)
                                    ->where('status', 'ACTIVE')
                                    ->orderBy('id', 'asc')
                                    ->first();

        if ($vencimiento) {
        //

            if ($vencimiento->cantidad_cajas_stock_anterior > 0) {
                // Hay stock en cantidad_cajas_stock_anterior
                if ($vencimiento->cantidad_cajas_stock_anterior >= $cantidad_descontar) {
                    // Descontar del stock anterior
                    $vencimiento->cantidad_cajas_stock_anterior -= $cantidad_descontar;
                } else {
                    // Descontar del stock anterior y el excedente de cantidad vendida
                    $vencimiento->cantidad_vendida += ($cantidad_descontar - $vencimiento->cantidad_cajas_stock_anterior);
                    $vencimiento->cantidad_cajas_stock_anterior = 0;
                }
            } else {
                // No hay stock en cantidad_cajas_stock_anterior, descontar de cantidad vendida
                $vencimiento->cantidad_vendida += $cantidad_descontar;
            }

            // Verificar si cantidad_vendida llegó a cantidad_ingresada y actualizar el estado
            if ($vencimiento->cantidad_vendida >= $vencimiento->cantidad_ingresada) {
                $vencimiento->status = 'DESACTIVE';
            }

        // Guardar los cambios
        $vencimiento->save();



        }
    }

}
