<?php

use App\Http\Controllers\ImprimirController;
use Illuminate\Support\Facades\Route;




Route::get('/list', [\App\Http\Livewire\Cotizaciones\ListComponent::class, '__invoke'])->middleware('auth')->name('cotizaciones.list');
Route::get('/crear', [\App\Http\Livewire\Cotizaciones\CreateComponent::class, '__invoke'])->middleware('auth')->name('cotizaciones.create');
