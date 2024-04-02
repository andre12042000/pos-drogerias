<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use App\Http\Livewire\Control\Vencimientos\ListComponent;
use App\Http\Livewire\Control\Temperatura\ListComponent as ListadoSeguimientosTemperatura;


Route::middleware([ PermissionMiddleware::class.':Acceso Parametros'])->group(function () {

    Route::get('vencimientos', [ListComponent::class, '__invoke'])->name('vencimientos');
    Route::get('temperatura', [ListadoSeguimientosTemperatura::class, '__invoke'])->name('temperatura');



});
