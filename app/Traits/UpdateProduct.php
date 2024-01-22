<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;

trait UpdateProduct
{

    public function discountProduct($product)
    {
        $producto = Product::find($product['id']);



        $stock_nuevo = $producto->stock - $product['quantity'];

        $producto->update([
            'stock'     => $stock_nuevo,
        ]);



        if($stock_nuevo <= $producto->stock_min AND $producto->stock_min > 0)
        {
                $this->sendNotifyLowStock($producto);
        }



    }

    public function addProduct($product)
    {
        $producto = Product::find($product['0']['id']);

        $producto->update([
            'stock'     => $producto->stock + $product['0']['quantity'],
        ]);

    }

    public function updatePrices($product)
    {
        $producto = Product::find($product['product_id']);
        $producto->update([
            'precio_compra'                    => $product['purchase_price'],
            'precio_caja'                    => $product['sales_price'],
            'precio_blister'            => $product['precio_blister'],
            'precio_unidad'       => $product['precio_unidad'],
        ]);

    }



    //Enviar notificacion si el inventario es bajo

    public function sendNotifyLowStock($product)
    {
        User::role(['Administrador'])->where('status', 'ACTIVO')
        ->each(function(User $user) use ($product){
           $user->notify(new LowStockNotification($product));
        });
    }


}
