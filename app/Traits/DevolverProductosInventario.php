<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait DevolverProductosInventario
{
    public function devolverproductos($saleDetails)
    {
        foreach ($saleDetails as $index => $item) {

            $detalle = $item;
            $producto = $item->product;
            $inventario = $item->product->inventario;

            if ($item->forma == 'disponible_caja') { //Si se vendio por caja
                $this->agregarPorCaja($producto, $detalle, $inventario);
            } elseif ($item->forma == 'disponible_blister') { //Si se vendio por blister
                $this->agregarPorBlister($producto, $detalle, $inventario);
            } else { //Si se vende por unidad
                $this->agregarPorUnidad($producto, $detalle, $inventario);
            }
        }
    }

    function agregarPorCaja($producto, $detalleVenta, $inventario)
    {

        $cantidad_cajas = $detalleVenta->quantity;

        $cantidad_blister_por_caja = $producto->contenido_interno_blister;
        $cantidad_unidades_por_caja = $producto->contenido_interno_unidad;

        //  dd($producto);

        $blister_devolver = $cantidad_cajas * $cantidad_blister_por_caja;
        $unidades_devolver = $cantidad_cajas * $cantidad_unidades_por_caja;


        $nuevo_stock_cajas = $inventario->cantidad_caja + $cantidad_cajas;
        $nuevo_stock_blisters = $inventario->cantidad_blister + $blister_devolver;
        $nuevo_stock_unidad = $inventario->cantidad_unidad +  $unidades_devolver;


        $inventario->update([
            'cantidad_caja' => $nuevo_stock_cajas,
            'cantidad_blister' => $nuevo_stock_blisters,
            'cantidad_unidad' => $nuevo_stock_unidad,
        ]);


        return true;
    }

    function agregarPorBlister($producto, $detalleVenta, $inventario)
    {
        $blisters_devueltos = $detalleVenta->quantity;

        $cantidad_blister_por_caja = $producto->contenido_interno_blister;
        $cantidad_unidades_por_caja = $producto->contenido_interno_unidad;
        $unidades_por_blister = $cantidad_unidades_por_caja / $cantidad_blister_por_caja;


        $stock_actual_blister = $inventario->cantidad_blister;
        $stock_actual_unidad = $inventario->cantidad_unidad;


        $nuevas_unidades = $unidades_por_blister * $blisters_devueltos;

        $nuevo_stock_unidad = $nuevas_unidades + $stock_actual_unidad;
        $nuevo_stock_bliter = $stock_actual_blister + $blisters_devueltos;



        $inventario->update([
            'cantidad_blister' => $nuevo_stock_bliter,
            'cantidad_unidad' => $nuevo_stock_unidad,
        ]);

        return true;
    }

    function agregarPorUnidad($producto, $detalleVenta, $inventario)
    {
        $stock_actual_unidades = $inventario->cantidad_unidad;
        $nuevo_stock = $stock_actual_unidades + $detalleVenta->quantity;

        $inventario->update([
            'cantidad_unidad' => $nuevo_stock,
        ]);

        return true;
    }
}
