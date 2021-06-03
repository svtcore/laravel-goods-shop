@extends('manager.layouts.master')
@section('title', __('manager_order_edit.title'))
@section('content')
<h4 class="category_header">{{ __('manager_order_edit.title', ['order_id' => $order->order_id ]) }}</h4>
<div class="container">
<form action="{{ route('manager.order.update',['id' => $order->order_id ]) }}" method="POST">
@method('PUT')
@csrf
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col" class="w-50">{{ __('manager_order_edit.order_info') }}</th>
      <th scope="col" class="w-50">{{ __('manager_order_edit.contact_info') }}</th>
    </tr>
  </thead>
  <tbody>
     <input type="hidden" name="type" value="edit_data" />
      <tr>
        <td>
        <div class="form-row">
            <div class="form-group col-md-12">
                <div class="list-group">
                    @foreach($order->order_products as $oproduct)
                        <a class="list-group-item list-group-item-action" id="product_block_{{ $oproduct->products->product_id }}">
                          @if (app()->getLocale() == "en") 
                          {{ $oproduct->products->names->product_name_lang_en }}
                          @elseif (app()->getLocale() == "de")
                          {{ $oproduct->products->names->product_name_lang_de }}
                          @elseif (app()->getLocale() == "uk")
                          {{ $oproduct->products->names->product_name_lang_uk }}
                          @elseif (app()->getLocale() == "ru")
                          {{ $oproduct->products->names->product_name_lang_ru }}
                          @endif
                            <button type="button" class="btn btn-outline-primary float-right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                              </svg>
                            </button>
                            <input name="product_count_{{ $oproduct->products->product_id }}" class="edit-product-count form-control w_15 text-center float-right" min="0" data-id="{{ $oproduct->products->product_id }}" data-each="{{ $oproduct->products->product_price }}" type="number" value="{{ $oproduct->order_p_count }}"/>
                        </a>
                    @endforeach
                    <input type="button" class="btn btn-primary w-100" onclick="show_deleted()" value="{{ __('manager_order_edit.showhide') }}"></input>
                </div> 
        </div>
        </td>
        <td>
        <div class="form-row">
        <div class="form-group col-md-6">
                  <label for="inputAddress2">{{ __('manager_order_edit.f_name') }}</label>
                  <input type="text" class="form-control @error('f_name') is-invalid @enderror" name="f_name" value="{{ $order->order_fname }}">
                  @if ($errors->has('f_name'))
                    <div class="error">
                        {{ $errors->first('f_name') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label for="inputAddress2">{{ __('manager_order_edit.l_name') }}</label>
                  <input type="text" class="form-control @error('l_name') is-invalid @enderror" name="l_name" value="{{ $order->order_lname }}">
                  @if ($errors->has('l_name'))
                    <div class="error">
                        {{ $errors->first('l_name') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('manager_order_edit.phone') }}</label>
                  <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $order->order_phone }}">
                  @if ($errors->has('phone'))
                    <div class="error">
                        {{ $errors->first('phone') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label for="inputAddress2">{{ __('manager_order_edit.street') }}</label>
                  <input type="text" class="form-control @error('street') is-invalid @enderror" name="street" value="{{ $order->user_addresses->user_str_name }}">
                  @if ($errors->has('street'))
                    <div class="error">
                        {{ $errors->first('street') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <label for="inputZip">{{ __('manager_order_edit.house') }}</label>
                  <input type="text" class="form-control @error('house') is-invalid @enderror" name="house" value="{{ $order->user_addresses->user_house_num }}">
                  @if ($errors->has('house'))
                    <div class="error">
                        {{ $errors->first('house') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <label for="inputZip">{{ __('manager_order_edit.apart') }}</label>
                  <input type="text" class="form-control @error('appart') is-invalid @enderror" name="appart" value="{{ $order->user_addresses->user_apart_num }}">
                  @if ($errors->has('appart'))
                    <div class="error">
                        {{ $errors->first('appart') }}
                    </div>
                  @endif
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="inputZip">{{ __('manager_order_edit.ent') }}</label>
                  <input type="text" class="form-control @error('entrance') is-invalid @enderror" name="entrance" value="{{ $order->user_addresses->user_ent_num }}">
                  @if ($errors->has('entrance'))
                    <div class="error">
                        {{ $errors->first('entrance') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <label for="inputZip">{{ __('manager_order_edit.code') }}</label>
                  <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $order->order_code }}">
                  @if ($errors->has('code'))
                    <div class="error">
                        {{ $errors->first('code') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label for="inputState">{{ __('manager_order_edit.payment_type') }}</label>
                  {!! Form::select('size', $payment, $order->f_pay_t_id, ['name' => 'payment', 'class'=>'form-control']); !!}
                </div>
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('manager_order_edit.status') }}</label>
                    <select class="form-control @error('status') is-invalid @enderror" name="status">
                        <option value="created" @if ($order->order_status == "created") selected @endif>created</option>
                        <option value="processing" @if ($order->order_status == "processing") selected @endif>processing</option>
                        <option value="completed" @if ($order->order_status == "completed") selected @endif>completed</option>
                        <option value="canceled" @if ($order->order_status == "canceled") selected @endif>canceled</option>
                    </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('manager_order_edit.note') }}</label>
                    <textarea class="form-control @error('note') is-invalid @enderror" name="note" id="exampleFormControlTextarea1" rows="3">{{ $order->order_note }}</textarea>
                </div>
              </div>
              </form>
              <hr>
              <h5><label>{{ __('manager_order_edit.total') }}: </label><span id="total_price"> {{ $order->order_full_price }} ₴</span></h5>
              <button  class="btn btn-primary w-100" type="submit" >{{ __('manager_order_edit.save') }}</button>
        </td>
    </tr>
  </tbody>
</table>
</form>
</div>
@endsection
@section('footer')
@parent
<script src="{{ asset('assets/js/manager.js') }}"></script>
@endsection
