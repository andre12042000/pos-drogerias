<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;


        return view('password.update', compact('user'))->with('info', 'Para poder continuar es necesario que actualice su contraseña');
    }
}
