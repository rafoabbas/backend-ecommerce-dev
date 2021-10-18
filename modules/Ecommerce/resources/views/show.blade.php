@extends('ecommerce::layouts.master')

@section('content')
    <h1>{{ $product->name }}</h1>


    <p>
        This view is loaded from module: {!! config('ecommerce.name') !!}
    </p>
@endsection
