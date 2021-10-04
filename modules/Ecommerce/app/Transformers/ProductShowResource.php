<?php

namespace Modules\Ecommerce\App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowResource extends JsonResource
{

    protected $defaultSelectedAttributeIds;

    public function __construct($resource, $defaultSelectedAttributeIds = [])
    {
        parent::__construct($resource);

        $this->defaultSelectedAttributeIds = $defaultSelectedAttributeIds;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'productId' => $this->id,
            'vendorId' => $this->vendor_id,
            'vendorName' => optional($this->vendor)->name,
            'brandId' => $this->brand_id,
            'brandName' => optional($this->brand)->name,
            'name' => $this->name,
            'description' => $this->description,
            'content' => $this->content,
            'slug' => $this->slug,
            'discountPrice' => $this->discount_price,
            'originalPrice' => $this->original_price,
            'quantity' => $this->quantity,
            'modelNo' => $this->model_no,
            'barcode' => $this->barcode,
            'sku' => $this->sku,
            'productAttributes' => $this->showProductAttributes($this)
        ];
    }


    /**
     * @param $product
     * @return array|mixed
     */
    private function showProductAttributes($product){
        $lastSelectedAttribute = $product->productAttributes()
            ->join('attribute_sets', 'product_attributes.attribute_set_id', '=', 'attribute_sets.id')
            ->orderBy('attribute_sets.order','DESC')
            ->select('product_attributes.attribute_set_id','product_attributes.attribute_id', 'attribute_sets.slug','attribute_sets.title','attribute_sets.display_layout')
            ->value('attribute_set_id');

        return $this->recursiveAttributes(
            $product,
            [],
            null,
            [],
            null,
            $lastSelectedAttribute
        );
    }


    /**
     * @param $product
     * @param array $selectedAttributeData
     * @param null $productVariationIds
     * @param array $data
     * @param null $defaultVariationId
     * @param null $lastSelectedAttribute
     * @return array|mixed
     */
    public function recursiveAttributes(
        $product,
        $selectedAttributeData = [],
        $productVariationIds = null,
        $data = [],
        $defaultVariationId = null,
        $lastSelectedAttribute = null
    ){

        //Orderi 0 olan attribute_setin getirilmesi
        $selectedAttribute = $product->productAttributes()
            ->join('attribute_sets', 'product_attributes.attribute_set_id', '=', 'attribute_sets.id')
            ->whereNotIn('attribute_set_id', $selectedAttributeData)
            ->orderBy('attribute_sets.order','ASC')
            ->select('product_attributes.attribute_set_id','product_attributes.attribute_id', 'attribute_sets.slug','attribute_sets.title','attribute_sets.display_layout')
            ->first();

        //Eger datalar yoxdursa o zaman burada datanı gətir
        if (is_null($selectedAttribute)){
            return $data;
        }

        $selectedAttributeValue = $selectedAttribute->attribute_set_id;

        $data[] = $this->setAttributeSet($product, $selectedAttribute, $productVariationIds, $lastSelectedAttribute);

        $selectedAttributeData[] = $selectedAttributeValue;

        $query = $product->productAttributes()
            ->whereIn('attribute_set_id', $selectedAttributeData)
            ->where('attribute_id', $selectedAttribute->attribute_id);

        if($productVariationIds){
            $query = $query->whereIn('product_variation_id', $productVariationIds);
        }

        $productVariationIds = $query->pluck('product_variation_id')->toArray();


        return $this->recursiveAttributes($product, $selectedAttributeData, $productVariationIds, $data, $defaultVariationId, $lastSelectedAttribute);
    }

    /**
     * @param $product
     * @param $selectedAttribute
     * @param $productVariationIds
     * @param $lastSelectedAttribute
     * @return array
     */
    private function setAttributeSet(
        $product,
        $selectedAttribute,
        $productVariationIds,
        $lastSelectedAttribute
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
                $lastSelectedAttribute
            )
        ];
    }

    /**
     * @param $product
     * @param $selectedAttributeValue
     * @param $productVariationIds
     * @param $lastSelectedAttribute
     * @return array
     */
    private function setAttribute(
        $product,
        $selectedAttributeValue,
        $productVariationIds,
        $lastSelectedAttribute
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
            ->map(function ($value) use ($lastSelectedAttribute){
                return $this->arrayProductAttribute($value, $lastSelectedAttribute);
            })->toArray();
    }


    /**
     * @param $value
     * @return array
     */
    public function arrayProductAttribute($value): array
    {
        return [
            'attributeId' => optional($value->attribute)->id,
            'attributeTitle' => optional($value->attribute)->title,
            'attributeSlug' => optional($value->attribute)->slug,
            'attributeColor' => optional($value->attribute)->color,
            'productVariationId'     => optional($value->productVariation)->id,
            'productVariationSlug'     => optional($value->productVariation)->slug,
            'productVariationQuantity'     => optional($value->productVariation)->quantity,
        ];
    }
}
