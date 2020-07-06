<!-- <ul class="nav sidebar-menu">Category
    @foreach($categories as $category)
        @if($category->children()->count() > 0)
            <li>@include('layouts.front.category-sidebar-sub', ['subs' => $category->children])</li>
        @else
            <li @if(request()->segment(2) == $category->slug) class="active" @endif><a href="{{ route('front.category.slug', $category->slug) }}">{{ $category->name }}</a></li>
        @endif
    @endforeach
</ul> -->
<ul class="nav sidebar-menu">Size
    @foreach($size as $size)
        <li><a href="{{route('search.product')}}?q={{request()->input('q')}}&brand={{request()->input('brand')}}&size={{$size->id}}&color={{request()->input('color')}}">{{ $size->value }}</a></li>
    @endforeach
</ul>
<ul class="nav sidebar-menu">Color
    @foreach($color as $color)
        <li><a href="{{route('search.product')}}?q={{request()->input('q')}}&brand={{request()->input('brand')}}&size={{request()->input('size')}}&color={{$color->id}}">{{ $color->value }}</a></li>
    @endforeach
</ul>
<ul class="nav sidebar-menu">Brand
    @foreach($brands as $brand)
        <li><a href="{{route('search.product')}}?q={{request()->input('q')}}&brand={{$brand->id}}&size={{request()->input('size')}}&color={{request()->input('color')}}">{{ $brand->name }}</a></li>
    @endforeach
</ul>
<ul class="nav sidebar-menu">Price
    <!-- <form action="{{route('search.product')}}?q={{request()->input('q')}}" method="GET"> -->
        <li><input type="number" id="price_min" name="price_min" placeholder="Min" value="{{request()->input('price_min')}}"></li>
        <li><input type="number" id="price_max" name="price_max" placeholder="Max" value="{{request()->input('price_max')}}"></li>
        <li>&nbsp;</li>
        <li>
            <span class="input-group-btn">
                <button onclick="searchProduct()" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> Search</button>
            </span>
        </li>
    <!-- </form> -->
</ul>
<script>
    function searchProduct() {
        priceMin = $('#price_min').val();
        priceMax = $('#price_max').val();
        location.href="{{route('search.product')}}?q={{request()->input('q')}}&brand={{request()->input('brand')}}&size={{request()->input('size')}}&color={{request()->input('color')}}&price_min="+priceMin+"&price_max="+priceMax;
    }
</script>