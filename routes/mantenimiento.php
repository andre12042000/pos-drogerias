<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController;
use App\Http\Livewire\Mantenimiento\Equipos\ListarComponent;
use App\Http\Livewire\Orders\EditComponent;
use Spatie\Permission\Middlewares\PermissionMiddleware;



Route::get('/equipos/listado', ListarComponent::class)->name('equipos.list')->middleware([ PermissionMiddleware::class.':Acceso Equipo']);


