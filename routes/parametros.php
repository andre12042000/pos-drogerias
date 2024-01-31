<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Parametros\BrandController;
use App\Http\Controllers\Parametros\CategoryController;
use App\Http\Livewire\Presentacion\ListComponent;
use Spatie\Permission\Middlewares\PermissionMiddleware;


Route::middleware([ PermissionMiddleware::class.':Acceso Gestion Parametros'])->group(function () {

Route::get('eliminar', [CategoryController::class, 'destroy'])->name('destroycategory');
Route::get('categorias', [CategoryController::class, 'index'])->name('category');
Route::get('marcas', [BrandController::class, 'index'])->name('brand');

Route::get('presentaciones', ListComponent::class)->name('presentaciones');
Route::get('laboratorios', [\App\Http\Livewire\Laboratorio\ListComponent::class, '__invoke'])->name('laboratorios');
Route::get('subacateogoria', [\App\Http\Livewire\Subcategoria\ListComponent::class, '__invoke'])->name('subcategoria');
Route::get('ubicacion', [\App\Http\Livewire\Ubicacion\ListComponent::class, '__invoke'])->name('ubicacion');
Route::get('motivo-anulacion', [\App\Http\Livewire\Anulacion\ListComponent::class, '__invoke'])->name('motivoanulacion');


});
