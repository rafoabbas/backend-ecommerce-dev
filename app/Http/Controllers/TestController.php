<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Ecommerce\App\Models\Category;
use Modules\Ecommerce\App\Models\Product;

class TestController extends Controller
{
    public function index(Request $request){

        $category = Category::where('id', 5)->first();
        DB::enableQueryLog();

        $products = $category->products()->limit($request->get('limit', 100))->get();

//        dump(now()->toDate());
//        $products = Product::limit($request->get('limit', 100))->get();
//        dd(now()->toDate());

        dd(DB::getQueryLog());

        //dd($request->getContent());
    }
}
