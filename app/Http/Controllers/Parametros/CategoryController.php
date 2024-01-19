<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {

        return view('parametros.category.index');
    }
    public function destroy($id)
    {
        $category = Category::find($id);
            $category->delete();

            return view('parametros.category.index');


    }
}
