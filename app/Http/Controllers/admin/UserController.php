<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function __construct()
    {


    }
    public function index()
    {
        return view('admin.user.index');
    }
}
