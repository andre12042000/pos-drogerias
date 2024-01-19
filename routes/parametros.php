<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Parametros\BrandController;
use App\Http\Controllers\Parametros\CategoryController;
use Spatie\Permission\Middlewares\PermissionMiddleware;


Route::middleware([ PermissionMiddleware::class.':Acceso Gestion Parametros'])->group(function () {

Route::get('eliminar', [CategoryController::class, 'destroy'])->name('destroycategory');
Route::get('categorias', [CategoryController::class, 'index'])->name('category');
Route::get('marcas', [BrandController::class, 'index'])->name('brand');
});