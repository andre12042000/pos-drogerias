<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::all();

        return view('inventario.purchase.index', compact('purchases'));

    }

    public function edit(Purchase $purchase)
    {
        return view('inventario.purchase.edit', compact('purchase'));
    }
}
