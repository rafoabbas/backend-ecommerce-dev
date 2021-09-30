<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request){
        $request_fields = file_get_contents('php://input');

        dd($request->getContent());
    }
}
