@extends('user.layouts.master')
<title>
    @if (app()->getLocale() == "en")
    {{ $product_info->names->product_name_lang_en }}
    @elseif (app()->getLocale() == "de")
    {{ $product_info->names->product_name_lang_de }}
    @elseif (app()->getLocale() == "uk")
    {{ $product_info->names->product_name_lang_uk }}
    @elseif (app()->getLocale() == "ru")
    {{ $product_info->names->product_name_lang_ru }}
    @endif
</title>
@section('content')
<div class="product-path">
    <span><a href="{{ route('user.main.page') }}">Main</a> -> <a href="{{ route('user.categories.show', ['id' => $product_info->categories->catg_id]) }}">
            @if (app()->getLocale() == "en")
            {{ $product_info->categories->catg_name_en }} </a> -> {{ $product_info->names->product_name_lang_en }}
        @elseif (app()->getLocale() == "de")
        {{ $product_info->categories->catg_name_de }} </a> -> {{ $product_info->names->product_name_lang_de }}
        @elseif (app()->getLocale() == "uk")
        {{ $product_info->categories->catg_name_uk }} </a> -> {{ $product_info->names->product_name_lang_uk }}
        @elseif (app()->getLocale() == "ru")
        {{ $product_info->categories->catg_name_ru }} </a> -> {{ $product_info->names->product_name_lang_ru }}
        @endif
    </span>
</div>
<div class="product-info-block">
    <div class="product-image-block">
        <div class="slider">
            <div class="slider__wrapper">
                @php $k = 0; @endphp
                @foreach ($product_info->images as $index => $img)
                @if ($img->product_image_name != null)
                <div class="slider__item">
                    <div><img class="product-image" src="{{ asset('storage/assets/img/products')}}/{{$img->product_image_name}}"></img></div>
                </div>
                @php $k = 1; @endphp
                @endif
                @if(($index == (count($product_info->images)-1) && ($img->product_image_name == null) && ($k != 1)))
                <div class="slider__item">
                    <div><img class="product-image" src="{{ asset('storage/assets/img/products/no-image.jpg')}}"></img></div>
                </div>
                @endif
                @endforeach
            </div>
            <a class="slider__control slider__control_left" href="#" role="button"></a>
            <a class="slider__control slider__control_right slider__control_show" href="#" role="button"></a>
        </div>
    </div>
    <div class="product-details-block">
        <h4 class="product-detail-header">
            @if (app()->getLocale() == "en")
            {{ $product_info->names->product_name_lang_en }}
            @elseif (app()->getLocale() == "de")
            {{ $product_info->names->product_name_lang_de }}
            @elseif (app()->getLocale() == "uk")
            {{ $product_info->names->product_name_lang_uk }}
            @elseif (app()->getLocale() == "ru")
            {{ $product_info->names->product_name_lang_ru }}
            @endif
        </h4>
        <hr>
        <div class="product-desc-body">
            <span>
                @if (app()->getLocale() == "en")
                {{ $product_info->descriptions->product_desc_lang_en }}
                @elseif (app()->getLocale() == "de")
                {{ $product_info->descriptions->product_desc_lang_de }}
                @elseif (app()->getLocale() == "uk")
                {{ $product_info->descriptions->product_desc_lang_uk }}
                @elseif (app()->getLocale() == "ru")
                {{ $product_info->descriptions->product_desc_lang_ru }}
                @endif
            </span>
            <div><span class="price-detail"><b>{{ $product_info->product_price }} ₴</b></span>
                <div class="counter-detail">
                    <label>{{ __('user_product.count') }}</label>
                    <input class="input-number-decrement" data-id="{{ $product_info->product_id }}" value="-" data-min="0">
                    <input id="standard" class="input-number" type="text" value="1">
                    <input class="input-number-increment" data-id="{{ $product_info->product_id }}" value="+" data-max="10">
                </div>
            </div>
            </br>
        </div>
        @if($product_info->product_exst != 0)
        <div class="buy-button">
            <a class="add-button" id="product_{{ $product_info->product_id }}" onclick="addToCart({{ $product_info->product_id }},1);"><b>{{ __('user_product.add_to_cart')}}</b></a>
        </div>
        @else
        <div class="buy-button">
            <h5 class="h_center"><b>{{ __('user_product.unavailable')}}</b></h5>
        </div>
        @endif
        @auth('admin')
        <div class="prod_del_edit">
            <form action="{{ route('admin.products.delete', ['id' => $product_info->product_id]) }}" method="POST">
                <a class="btn btn-outline-primary w_45 float-left" href="{{ route('admin.products.edit', ['id' => $product_info->product_id]) }}" role="button" type="button">
                    <b>{{ __('admin_product.edit_btn')}}</b>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                    </svg>
                </a>
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-outline-danger w_45 float-right">
                    <b>{{ __('admin_product.delete_btn')}}</b>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                    </svg>
                </button>
            </form>
        </div>
        @endauth
    </div>
</div>
@endsection
@section('footer')
@parent
<script src="{{ asset('assets/js/slider.js') }}"></script>
@endsection