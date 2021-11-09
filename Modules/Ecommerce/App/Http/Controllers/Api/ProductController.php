<?php

namespace Modules\Ecommerce\App\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Ecommerce\App\Models\Attribute;
use Modules\Ecommerce\App\Models\AttributeSet;
use Modules\Ecommerce\App\Models\Product;
use Modules\Ecommerce\App\Models\ProductAttribute;
use Modules\Ecommerce\App\Models\ProductVariation;
use Modules\Ecommerce\App\Transformers\ProductShowResource;

class ProductController extends Controller
{
    /**
     * Show the specified resource.
     * @param int $slug
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|ProductShowResource
     */
    public function show($slug, Product $product, Request $request)
    {
        if ($request->ajax()){
            return new ProductShowResource($product);
        }
        $product = json_decode(json_encode(new ProductShowResource($product)));
        return view('ecommerce::show', compact('product'));
    }

    /**
     * @param $slug
     * @param ProductVariation $productVariation
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|ProductShowResource
     */
    public function showProductVariation($slug, ProductVariation $productVariation, Request $request){
        if ($request->ajax()){
            return new ProductShowResource(
                $productVariation->product,
                $productVariation
            );
        }

        $product = json_decode(json_encode(new ProductShowResource(
            $productVariation->product,
            $productVariation
        )));

        return view('ecommerce::show', compact('product'));
    }

    public function index()
    {
        return view('ecommerce::index');
    }
}
