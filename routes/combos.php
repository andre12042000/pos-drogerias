<?php

use App\Http\Controllers\ImprimirController;
use Illuminate\Support\Facades\Route;




Route::get('/combo/editar/{combo_id}', [\App\Http\Livewire\Product\ComboEditComponent::class, '__invoke'])->middleware('auth')->name('editar');
