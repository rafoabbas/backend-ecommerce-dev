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
     * @return ProductShowResource
     */
    public function show($slug, $id)
    {
        $product = Product::where('is_variation', true)
            ->first();

        $defaultSelectedAttributeIds = $product->defaultProduct
            ->productAttributes()
            ->pluck('attribute_id')
            ->toArray();

        return new ProductShowResource($product, $defaultSelectedAttributeIds);
    }


    public function showVariation($slug, $id){


        $product = Product::where('is_variation', true)
            ->first();


        $defaultSelectedAttributeIds = $product
            ->variations()
            ->where('id', $id)
            ->first()
            ->productAttributes()
            ->pluck('attribute_id')->toArray();


        return $this->recursiveAttributes(
            $product,
            [],
            null,
            [],
            $defaultSelectedAttributeIds,
        );


    }

    public function recursiveAttributes($product, $selectedAttributeData = [], $productVariationIds = null, $data = [], $defaultSelectedAttributeIds = []){

        //Orderi 0 olan attribute_setin getirilmesi
        $selectedAttribute = $product->productAttributes()
                ->join('attribute_sets', 'product_attributes.attribute_set_id', '=', 'attribute_sets.id')
                ->whereNotIn('attribute_set_id', $selectedAttributeData)
                ->whereIn('attribute_id', $defaultSelectedAttributeIds)
                ->orderBy('attribute_sets.order','ASC')
                ->select('product_attributes.attribute_set_id','product_attributes.attribute_id', 'attribute_sets.slug','attribute_sets.title','attribute_sets.display_layout')
                ->first();

        //Eger datalar yoxdursa o zaman burada datanı gətir
        if (is_null($selectedAttribute)){
            return $data;
        }

        $selectedAttributeValue = $selectedAttribute->attribute_set_id;

        $data[] = $this->setAttributeSet($product, $selectedAttribute, $productVariationIds, $defaultSelectedAttributeIds);

        $selectedAttributeData[] = $selectedAttributeValue;

        $query = $product->productAttributes()
            ->whereIn('attribute_set_id', $selectedAttributeData)
            ->where('attribute_id', $selectedAttribute->attribute_id);

        if($productVariationIds){
            $query = $query->whereIn('product_variation_id', $productVariationIds);
        }

        $productVariationIds = $query->pluck('product_variation_id')->toArray();


        dump($productVariationIds);
        return $this->recursiveAttributes($product, $selectedAttributeData, $productVariationIds, $data, $defaultSelectedAttributeIds);
    }


    private function setAttributeSet(
        $product,
        $selectedAttribute,
        $productVariationIds,
        $defaultSelectedAttributeIds
    ): array
    {
        return [
            'attributeSetId' => $selectedAttribute->attribute_set_id,
            'attributeSetTitle' => $selectedAttribute->title,
            'attributeSetSlug' => $selectedAttribute->slug,
            'attributeSetDisplayLayout' => $selectedAttribute->display_layout,
            'attributes' => $this->setAttribute(
                $product,
                $selectedAttribute->attribute_set_id,
                $productVariationIds,
                $defaultSelectedAttributeIds
            )
        ];
    }

    private function setAttribute(
        $product,
        $selectedAttributeValue,
        $productVariationIds,
        $defaultSelectedAttributeIds
    ): array
    {

        return $product->productAttributes()
            ->when($productVariationIds, function ($q) use($productVariationIds){
                $q->whereIn('product_variation_id', $productVariationIds);
            })
            ->where('attribute_set_id' , $selectedAttributeValue)
            ->with('attribute')
            ->groupBy('attribute_id')
            ->get()
            ->map(function ($value) use ($defaultSelectedAttributeIds){
                return $this->arrayProductAttribute($value, $defaultSelectedAttributeIds);
            })->toArray();
    }

    public function arrayProductAttribute($value, $defaultSelectedAttributeIds): array
    {
        return [
            'attributeId' => $value->attribute_id,
            'attributeTitle' => optional($value->attribute)->title,
            'attributeSlug' => optional($value->attribute)->slug,
            'attributeColor' => optional($value->attribute)->color,
            'productVariationId'     => optional($value->productVariation)->id,
            'productVariationSlug'     => optional($value->productVariation)->slug,
            'productVariationQuantity'     => optional($value->productVariation)->quantity,
            'selected'  => in_array($value->attribute_id,$defaultSelectedAttributeIds)
        ];
    }


    public function index()
    {
        return view('ecommerce::index');
    }

}
