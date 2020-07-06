@extends('layouts.front.app')

@section('content')
    <div class="container">
        <hr>
        <div class="row">
            <div class="category-top col-md-12">
                <h2>Search Results</h2>
                @if(request()->input('q') != "")
                    <a href="{{route('search.product')}}?q=&brand={{request()->input('brand')}}&size={{request()->input('size')}}&color={{request()->input('color')}}" 
                        class="btn btn-info">{{request()->input('q')}} <i class="fa fa-remove"></i></a>
                @endif
                @if(request()->input('brand') != "")
                    @foreach($brands as $brand)
                        @if(request()->input('brand') == $brand->id)
                            <a href="{{route('search.product')}}?q={{request()->input('q')}}&brand=&size={{request()->input('size')}}&color={{request()->input('color')}}"
                                class="btn btn-info">{{$brand->name}} <i class="fa fa-remove"></i></a>
                        @endif
                    @endforeach
                @endif
                @if(request()->input('size') != "")
                    @foreach($size as $size)
                        @if(request()->input('size') == $size->id)
                            <a href="{{route('search.product')}}?q={{request()->input('q')}}&brand={{request()->input('brand')}}&size=&color={{request()->input('color')}}"
                                class="btn btn-info">{{$size->value}} <i class="fa fa-remove"></i></a>
                        @endif
                    @endforeach
                @endif
                @if(request()->input('color') != "")
                    @foreach($color as $color)
                        @if(request()->input('color') == $color->id)
                            <a href="{{route('search.product')}}?q={{request()->input('q')}}&brand={{request()->input('brand')}}&size={{request()->input('size')}}&color="
                                class="btn btn-info">{{$color->value}} <i class="fa fa-remove"></i></a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <hr>
        <div class="col-md-3">
            <div class="row">
                <h4>Filter</h4>
            </div>
            @include('front.categories.sidebar-category')
        </div>
        <div class="col-md-9">
            <div class="row">
                @include('front.products.product-list', ['products' => $products])
            </div>
        </div>
    </div>
@endsection