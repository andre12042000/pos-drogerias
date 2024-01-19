<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImpresoraController extends Controller
{
    public function index()
    {
        return view('setting.impresoras.index');
    }

    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }
}
