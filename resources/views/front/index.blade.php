@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="home"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ config('app.name') }}"/>
@endsection

@section('content')
    @include('layouts.front.home-slider')
    <section class="new-product t100 home">
        <div class="container">
            <div class="section-title b50">
                <h2>Best Seller</h2>
            </div>
            @include('front.products.product-list-best-seller', ['products' => $bestSeller])
            <div id="browse-all-btn"> <a class="btn btn-default browse-all-btn" href="{{ route('front.category.slug', $cat1->slug) }}" role="button">browse all items</a></div>
        </div>
    </section>
@endsection