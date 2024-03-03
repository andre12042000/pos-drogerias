<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ImprimirController;

Route::get('imprimir/{pago_id}', [ImprimirController::class, 'imprimirPagoRecibo'])->name('pago_venta_credito'); //Imprimir pago venta credito
