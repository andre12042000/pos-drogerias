<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();

        return view('parametros.brand.index', compact('brands'));
    }
}
