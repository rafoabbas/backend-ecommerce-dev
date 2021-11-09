<?php

namespace Modules\Ecommerce\App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowResource extends JsonResource
{


    protected $selectedProduct = null;

    public function __construct($resource, $selectedProduct = null)
    {
        parent::__construct($resource);

        $this->setSelectedProduct($selectedProduct);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        //Seçili məhsulun null olub olmaması üçün yoxlama
        $optionalSelectedProduct = optional($this->selectedProduct);
        return [
            'productId' => $this->id,
            'vendorId' => $this->vendor_id,
            'vendorName' => optional($this->vendor)->name,
            'brandId' => $this->brand_id,
            'brandName' => optional($this->brand)->name,
            'name' => $this->selectedProduct ? $optionalSelectedProduct->name : $this->name,
            'description' => $this->selectedProduct ? $optionalSelectedProduct->description : $this->description,
            'content' => $this->selectedProduct ? $optionalSelectedProduct->content : $this->content,
            'slug' => $this->selectedProduct ? $optionalSelectedProduct->slug : $this->slug,
            'discountPrice' => $this->selectedProduct ? $optionalSelectedProduct->discount_price : $this->discount_price,
            'originalPrice' => $this->selectedProduct ? $optionalSelectedProduct->original_price : $this->original_price,
            'quantity' => $this->selectedProduct ? $optionalSelectedProduct->quantity : $this->quantity,
            'modelNo' => $this->selectedProduct ? $optionalSelectedProduct->model_no : $this->model_no,
            'barcode' => $this->selectedProduct ? $optionalSelectedProduct->barcode : $this->barcode,
            'sku' => $this->selectedProduct ? $optionalSelectedProduct->sku : $this->sku,
            $this->mergeWhen($this->is_variation, [
                'productAttributes' => $this->showProductAttributes()
            ])
        ];
    }

    /**
     *
     * @param $product
     * @return array|mixed
     */
    private function showProductAttributes(): array
    {

        if (! $this->is_variation) return [];

        $defaultSelectedAttributeIds = $this->selectedProduct
            ->productAttributes()
            ->pluck('attribute_id')
            ->toArray();

        return $this->recursiveAttributes(
            $this,
            [],
            null,
            [],
            $defaultSelectedAttributeIds,
        );
    }


    /**
     * //TODO:: sekiller qismi elave edilecek
     * @param $product
     * @param array $selectedAttributeData
     * @param null $productVariationIds
     * @param array $data
     * @param array $defaultSelectedAttributeIds
     * @return array|mixed
     */
    public function recursiveAttributes(
        $product,
        $selectedAttributeData = [],
        $productVariationIds = null,
        $data = [],
        $defaultSelectedAttributeIds = []
    ){
        //Orderi 0 olan attribute_setin getirilmesi
        $selectedAttribute = $this->selectedAttributeQuery($product, $selectedAttributeData, $defaultSelectedAttributeIds);

        //Eger datalar yoxdursa o zaman burada datanı gətir
        if (is_null($selectedAttribute)){
            return $data;
        }

        //attributeSetId - bu variantların ana bilgisini göstərir
        $selectedAttributeValue = $selectedAttribute->attribute_set_id;

        // Product səhifəsində variantları göstərmək üçün
        $data[] = $this->setAttributeSet($product, $selectedAttribute, $productVariationIds, $defaultSelectedAttributeIds);

        // Productun hansı atributlarının eçili gələcəyini toplayır
        $selectedAttributeData[] = $selectedAttributeValue;

        $query = $product->productAttributes()
            ->whereIn('attribute_set_id', $selectedAttributeData)
            ->where('attribute_id', $selectedAttribute->attribute_id);

        if($productVariationIds){
            $query = $query->whereIn('product_variation_id', $productVariationIds);
        }

        $productVariationIds = $query->pluck('product_variation_id')->toArray();

        return $this->recursiveAttributes($product, $selectedAttributeData, $productVariationIds, $data, $defaultSelectedAttributeIds);
    }

    /**
     * @param $product
     * @param $selectedAttribute
     * @param $productVariationIds
     * @param $defaultSelectedAttributeIds
     * @return array
     */
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

    /**
     * @param $product
     * @param $selectedAttributeValue
     * @param $productVariationIds
     * @param $defaultSelectedAttributeIds
     * @return array
     */
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

    /**
     *
     * @param $value
     * @param $defaultSelectedAttributeIds
     * @return array
     */
    public function arrayProductAttribute(
        $value,
        $defaultSelectedAttributeIds
    ): array
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

    /**
     *
     * @param $product
     * @param array $selectedAttributeData
     * @param array $defaultSelectedAttributeIds
     * @return mixed
     */
    public function selectedAttributeQuery($product, array $selectedAttributeData = [], array $defaultSelectedAttributeIds = []){
        $query = $product->productAttributes()
            ->join('attribute_sets', 'product_attributes.attribute_set_id', '=', 'attribute_sets.id');

        if (count($selectedAttributeData)){
            $query->whereNotIn('attribute_set_id', $selectedAttributeData);
        }

        $query = $query->whereIn('attribute_id', $defaultSelectedAttributeIds)
            ->orderBy('attribute_sets.order','ASC')
            ->select('product_attributes.attribute_set_id','product_attributes.attribute_id', 'attribute_sets.slug','attribute_sets.title','attribute_sets.display_layout');
        return $query->first();
    }

    /**
     * Variyasiyalı məhsulun seçilmiş və ya keçərli variyasiyasını set edir
     * @param null $defaultProduct
     */
    protected function setSelectedProduct(
        $defaultProduct = null
    ){
        $this->selectedProduct = $defaultProduct ?? ($this->is_variation ? $this->defaultProduct()->first() : null);
    }
}
