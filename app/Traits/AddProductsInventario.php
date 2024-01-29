<?php

namespace App\Traits;

use App\Models\Inventario;
use App\Models\Product;
use App\Models\User;
use App\Models\Vencimientos;
use App\Notifications\LowStockNotification;

trait AddProductsInventario
{

    public function addProducts($details){

        $producto = Product::findOrFail($details['product_id']);
        $stock_actual = Inventario::where('product_id', $details['product_id'])->first();


        $nuevos_blister = 0;
        $nuevos_unidad = 0;
        $nuevas_cajas = $details['quantity'];


         if($producto->disponible_blister > 0 && $producto->contenido_interno_blister > 0){
            $nuevos_blister = $producto->contenido_interno_blister * $details['quantity'];
         }

         if($producto->disponible_unidad > 0 && $producto->contenido_interno_unidad > 0){
            $nuevos_unidad = $producto->contenido_interno_unidad * $details['quantity'];
         }

         $nueva_cantidad_cajas =  $stock_actual->cantidad_caja + $nuevas_cajas ;
         $nueva_cantidad_blister = $stock_actual->cantidad_blister + $nuevos_blister ;
         $nueva_cantidad_unidad =  $stock_actual->cantidad_unidad + $nuevos_unidad;

         $stock_actual->update([
            'cantidad_caja'     => $nueva_cantidad_cajas,
            'cantidad_blister'  => $nueva_cantidad_blister,
            'cantidad_unidad'   => $nueva_cantidad_unidad,
         ]);


         if($details['caducidad']){
            $this->registrarVencimientos($details);
        }



         return true;



    }

    function registrarVencimientos($detalles)
    {
        Vencimientos::create([
            'purchase_id'       => $detalles['purchase_id'],
            'product_id'        => $detalles['product_id'],
            'fecha_vencimiento' => $detalles['caducidad'],
            'lote'              => $detalles['lote'],
            'cantidad_ingresada'=> $detalles['quantity'],
            'cantidad_vendida'  => 0,
            'status'            => 'ACTIVE',
        ]);

    }


}
