<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function movie($id, Request $request)
    {
        
        return $id;
    }
}
