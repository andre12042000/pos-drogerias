<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ImpresoraController;
use Spatie\Permission\Middlewares\PermissionMiddleware;

Route::middleware([ PermissionMiddleware::class.':Acceso Configuración'])->group(function () {

Route::get('impresora', [ImpresoraController::class, 'index'])->name('impresoras');
Route::get('empresas', [EmpresaController::class, 'index'])->name('empresas');
});