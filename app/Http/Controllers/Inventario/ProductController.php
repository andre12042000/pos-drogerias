<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Chart\Layout;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();


        return view('inventario.products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findorfail($id);
        return view('inventario.products.show', compact('product'))->extends('adminlte::page');
     
    }

    public function low()
    {
        $products = Product::all();
        return view('inventario.products.low', compact('products'));
    }
}
