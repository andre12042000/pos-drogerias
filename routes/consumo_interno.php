<?php


use Illuminate\Support\Facades\Route;

use App\Http\Livewire\ConsumoInterno\ListComponent;
use App\Http\Livewire\ConsumoInterno\CreateComponent;

Route::get('/', [ListComponent::class, '__invoke'])->name('index');
Route::get('/create', [CreateComponent::class, '__invoke'])->name('create');
