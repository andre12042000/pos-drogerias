<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OrderServiceController extends Controller
{

/*     public function __construct()
    {
        $this->middleware('can:Modulo de seguridad')->only('index', 'create', 'edit', 'store', 'update', 'destroy');
    } */
    public function index()
    {

        return view('service.order.index');
    }

    public function create()
    {

        return view('service.order.create');
    }
}
