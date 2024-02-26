<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Terceros\ClientController;
use App\Http\Livewire\Client\DetailComponent;
use App\Http\Controllers\Terceros\ProviderController;
use Spatie\Permission\Middlewares\PermissionMiddleware;

Route::middleware([ PermissionMiddleware::class.':Acceso Gestion Terceros'])->group(function () {

    Route::get('clientes', [ClientController::class, 'index'])->name('client');
    Route::get('clientes/detalles/{cliente_id}', [DetailComponent::class, '__invoke'])->name('client.details');
    Route::get('proveedores', [ProviderController::class, 'index'])->name('provider');
});
