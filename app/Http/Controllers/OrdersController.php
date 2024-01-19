<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }

    public function create()
    {
        return view('orders.create');
    }

    public function show($id)
    {
        $empresa = Empresa::find(1);
        $order = Orders::find($id);



        return view('orders.show', compact('empresa', 'order'));
    }

}
