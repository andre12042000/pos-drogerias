<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Gastos\ListComponent;
use App\Http\Livewire\Gastos\EditGastoComponent;

Route::get('/', [ListComponent::class, '__invoke'])->name('list');
Route::get('edit/{gasto_id}', [EditGastoComponent::class, '__invoke'])->name('edit');
