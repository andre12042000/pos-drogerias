<?php

namespace App\Http\Controllers\Terceros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::all();

        return view('terceros.provider.index', compact('providers'));
    }
}
