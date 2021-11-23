@extends('user.layouts.master')
@section('title', __('user_order_complete.title'))
@section('content')
<h4 class="category_header">{{ __('user_order_complete.order_sent', ['order_id' => $order_id]) }}</h4>
<hr>
<div class="products-container">
    @if ($password != null)
    <div class="alert alert-primary order_complete_alert" role="alert">
        <h3>{{ __('user_order_complete.contact') }}</h3>
        {{ __('user_order_complete.password', ['password' =>  $password]) }}
    </div>
    @else
    <div class="alert alert-primary order_complete_alert" role="alert">
        <h3>{{ __('user_order_complete.contact') }}</h3>
    </div>
    @endif
</div>
<hr>
@endsection
@section('footer')
@parent
<script>
    eraseCookie("user_cart")
</script>
@endsection