@extends('ecommerce::layouts.master')

@section('content')
    <h1>{{ $product->name }}</h1>


    @foreach($product->productAttributes as $productAttribute)
        <p>{{ $productAttribute->title }}</p>
        <ul>
            @foreach($productAttribute->attribute as $attribute)
            <li><a href="{{ route('api.product.showVariation',[$attribute->productSlug,$attribute->productId]) }}">{{ $attribute->attributeTitle }} </a></li>
            @endforeach
        </ul>
    @endforeach
    <p>
        This view is loaded from module: {!! config('ecommerce.name') !!}
    </p>
@endsection
