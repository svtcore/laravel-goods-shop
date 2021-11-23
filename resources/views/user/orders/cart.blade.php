@extends('user.layouts.master')
@section('title', __('user_cart.title'))
@section('content')
<div class="order-block">
  <div class="wrapper center-block">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      @if ($order_products == null)
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingTwo">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="false">
              {{ __('user_cart.your_order') }}
            </a>
          </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse show" role="tabpanel">
          <div class="panel-body">
            <h4>{{ __('user_cart.empty_cart') }}</h4>
          </div>
        </div>
      </div>
      @else
      <div class="panel panel-default">
        <div class="panel-heading active" role="tab" id="headingOne">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOrder" aria-expanded="true" aria-controls="collapseOrder">
              {{ __('user_cart.order_details') }}
            </a>
          </h4>
        </div>
        <div id="collapseOrder" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingOrder">
          <div class="panel-body">
            @foreach($order_products as $product)
            <div class="row" id="cart_product_{{$product['id']}}">
              <div class="col-sm-8" id="product-order-info">
                <div class="order-product-image">
                  @if ($product['image'] != null)
                  <img src="{{ asset('storage/assets/img/products')}}/{{$product['image']}}"></img>
                  @else
                  <img src="{{ asset('storage/assets/img/products/no-image.jpg')}}"></img>
                  @endif
                </div>
                <div class="order-product-name">
                  <h6 id="order-name-label">{{ __('user_cart.name') }}</h6>
                  <hr>
                  <div>{{ $product['name'] }}</div>
                </div>
                <div class="order-product-quality">
                  <h6 id="order-quality-label">{{ __('user_cart.Quantity') }}</h6>
                  <hr>
                  <div>
                    <input class="input-number-decrement" data-id="{{ $product['id'] }}" value="-" data-min="0">
                    <input id="standard" class="input-number" type="text" value="{{ $product['count'] }}">
                    <input class="input-number-increment" data-id="{{ $product['id'] }}" value="+" data-max="10">
                  </div>
                </div>
                <div class="order-product-total-price">
                  <h6 id="order-total-label">{{ __('user_cart.price') }}</h6>
                  <hr>
                  <span id="product_total_price_{{$product['id']}}">{{ $product['total_product_price'] }}</span><span> {{ __('user_cart.currency') }}</span>
                  <input type="hidden" id="product_price_{{$product['id']}}" value="{{ $product['one_price']}}">
                </div>
              </div>
            </div>
            @endforeach
            <div class="col-sm-8" id="order-total-price">
              <span> {{ __('user_cart.total') }}: <h4 id="total-order-price">{{ $total }} ₴</h4></span>
              <input type="button" id="show-delivery-form" class="btn btn-primary" value="{{ __('user_cart.next_step') }}" />
              <hr>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-defaulsst">
        <div class="panel-heading" role="tab" id="headingTwo">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              {{ __('user_cart.delivery_information') }}
            </a>
          </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
          <div class="panel-body">
            <div id="order-delivery-data">
              <form method="POST" action="{{ route('user.orders.store') }}">
                @csrf
                <input type="hidden" name="city_id" value="1" />
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="inputEmail4">{{ __('user_cart.f_name') }}</label>
                    <input type="input" class="form-control @error('f_name') is-invalid @enderror" name="f_name" minlength="1" maxlength="50" required value="{{ old('f_name') }}">
                    @if ($errors->has('f_name'))
                    <div class="error">
                      {{ $errors->first('f_name') }}
                    </div>
                    @endif
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inputPassword4">{{ __('user_cart.l_name') }}</label>
                    <input type="input" class="form-control @error('l_name') is-invalid {{ $errors->first('l_name') }} @enderror" name="l_name" minlength="1" maxlength="50" required value="{{ old('l_name') }}">
                    @if ($errors->has('l_name'))
                    <div class="error">
                      {{ $errors->first('l_name') }}
                    </div>
                    @endif
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inputPassword4">{{ __('user_cart.phone') }}</label>
                    <input type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" minlength="10" maxlength="25" required value="{{ old('phone') }}">
                    @if ($errors->has('phone'))
                    <div class="error">
                      {{ $errors->first('phone') }}
                    </div>
                    @endif
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="inputAddress2">{{ __('user_cart.street') }}</label>
                    <input type="text" class="form-control @error('street') is-invalid @enderror" name="street" minlength="1" maxlength="100" required value="{{ old('street') }}">
                    @if ($errors->has('street'))
                    <div class="error">
                      {{ $errors->first('street') }}
                    </div>
                    @endif
                  </div>
                  <div class="form-group col-md-3">
                    <label for="inputZip">{{ __('user_cart.house') }}</label>
                    <input type="text" class="form-control @error('house') is-invalid @enderror" name="house" minlength="1" maxlength="5" required value="{{ old('house') }}">
                    @if ($errors->has('house'))
                    <div class="error">
                      {{ $errors->first('house') }}
                    </div>
                    @endif
                  </div>
                  <div class="form-group col-md-3">
                    <label for="inputZip">{{ __('user_cart.apart') }}</label>
                    <input type="text" class="form-control @error('appart') is-invalid @enderror" name="appart" minlength="1" maxlength="5" value="{{ old('appart') }}">
                    @if ($errors->has('appart'))
                    <div class="error">
                      {{ $errors->first('appart') }}
                    </div>
                    @endif
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label for="inputZip">{{ __('user_cart.entrance') }}</label>
                    <input type="text" class="form-control @error('entrance') is-invalid @enderror" name="entrance" minlength="1" maxlength="5" value="{{ old('entrance') }}">
                    @if ($errors->has('entrance'))
                    <div class="error">
                      {{ $errors->first('entrance') }}
                    </div>
                    @endif
                  </div>
                  <div class="form-group col-md-3">
                    <label for="inputZip">{{ __('user_cart.code') }}</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" minlength="1" maxlength="10" value="{{ old('code') }}">
                    @if ($errors->has('code'))
                    <div class="error">
                      {{ $errors->first('code') }}
                    </div>
                    @endif
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inputState">{{ __('user_cart.payment_type') }}</label>
                    {!! Form::select('size', $payment, '1', ['name' => 'payment', 'class'=>'form-control']); !!}
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('user_cart.note') }}</label>
                    <textarea class="form-control @error('note') is-invalid @enderror" name="note" minlength="1" maxlength="1000" rows="3">{{ old('note') }}</textarea>
                    <button type="submit" name="submit" id="btn-confirm-order" class="btn btn-primary">{{ __('user_cart.confirm_order') }}</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
</div>
</div>
@endsection
@section('footer')
@parent
@endsection