@extends('user.layouts.master')
<title>
    @if (app()->getLocale() == "en")
    {{ $category_products[0]->catg_name_en }}
    @elseif (app()->getLocale() == "de")
    {{ $category_products[0]->catg_name_de }}
    @elseif (app()->getLocale() == "uk")
    {{ $category_products[0]->catg_name_uk }}
    @elseif (app()->getLocale() == "ru")
    {{ $category_products[0]->catg_name_ru }}
    @endif
</title>
@section('content')
<h4 class="category_header">
    @if (app()->getLocale() == "en")
    {{ $category_products[0]->catg_name_en }}
    @elseif (app()->getLocale() == "de")
    {{ $category_products[0]->catg_name_de }}
    @elseif (app()->getLocale() == "uk")
    {{ $category_products[0]->catg_name_uk }}
    @elseif (app()->getLocale() == "ru")
    {{ $category_products[0]->catg_name_ru }}
    @endif
</h4>
<hr>
<div class="products-container">
    @foreach ($category_products as $product)
    @foreach ($product->products as $product_info)
    <div class="product-item">
        <div class="image-container">
            <a href="{{ route('user.products.show', ['id' => $product_info->product_id]) }}" target="_blank">
                @if ($product_info->images[0]->product_image_name != null)
                <img src="{{ asset('storage/assets/img/products')}}/{{$product_info->images[0]->product_image_name}}"></a>
            @else
            <img src="{{ asset('storage/assets/img/products/no-image.jpg')}}"></img></a>
            @endif
            <div class="after">
                @if (app()->getLocale() == "en")
                {{ $product_info->descriptions->product_desc_lang_en }}
                @elseif (app()->getLocale() == "de")
                {{ $product_info->descriptions->product_desc_lang_de }}
                @elseif (app()->getLocale() == "uk")
                {{ $product_info->descriptions->product_desc_lang_uk }}
                @elseif (app()->getLocale() == "ru")
                {{ $product_info->descriptions->product_desc_lang_ru }}
                @endif
            </div>
        </div>
        <div class="product-list">
            <span class="product-link"><a href="{{ route('user.products.show', ['id' => $product_info->product_id]) }}" target="_blank"><b>
                        @if (app()->getLocale() == "en")
                        {{ $product_info->names->product_name_lang_en }}
                        @elseif (app()->getLocale() == "de")
                        {{ $product_info->names->product_name_lang_de }}
                        @elseif (app()->getLocale() == "uk")
                        {{ $product_info->names->product_name_lang_uk }}
                        @elseif (app()->getLocale() == "ru")
                        {{ $product_info->names->product_name_lang_ru }}
                        @endif
                    </b></a></span>
            <hr class="divider">
            <div><span class="price"><b>{{ $product_info->product_price }} {{ __('user_category.currency') }}</b></span>
                <div class="number_counter">
                    <input class="input-number-decrement" data-id="{{ $product_info->product_id }}" value="-" data-min="0">
                    <input id="standard" class="input-number" type="text" value="1">
                    <input class="input-number-increment" data-id="{{ $product_info->product_id }}" value="+" data-max="10">
                </div>
                </br>
            </div>
            <div class="quick-buy-button">
                <a class="add-button" id="product_{{ $product_info->product_id }}" onclick="addToCart({{ $product_info->product_id }},1);"><b>{{ __('user_category.to_cart') }}</b></a>
            </div>
        </div>
    </div>
    @endforeach
    @endforeach
</div>
<hr>
<div class="d-flex justify-content-center">
    {!! $category_products->links() !!}
</div>
@endsection
@section('footer')
@parent
@endsection