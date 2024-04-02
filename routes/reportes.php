<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reportes\ReportesController;
use Spatie\Permission\Middlewares\PermissionMiddleware;

Route::middleware([ PermissionMiddleware::class.':Acceso Reportes'])->group(function () {
    Route::get('dia', [ReportesController::class, 'dia'])->name('reporte.dia');
Route::get('fechas', [ReportesController::class, 'fecha'])->name('reporte.fecha');
Route::get('dia-export', [ReportesController::class, 'export'])->name('reporte.export.dia');
Route::get('dia-exportfecha/{desde}/{hasta}', [ReportesController::class, 'exportfecha'])->name('reporte.export.fecha');
});
