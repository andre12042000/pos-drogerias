<?php

namespace App\Http\Controllers\Terceros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function __construct()
    {
       
    }
    public function index()
    {
        $clients = Client::all();

        return view('terceros.client.index', compact('clients'));
    }
}
