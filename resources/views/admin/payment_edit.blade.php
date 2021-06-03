@extends('admin.layouts.master')
@section('title', __('admin_payment_edit.title'))
@section('content')
<div class="container">
<form action="{{ route('admin.payment.update', ['id' => $payment->pay_t_id]) }}" method="POST">
@method('PUT')
@csrf
<table class="table data_small_table">
  <thead>
    <tr>
      <th scope="col"><h4 class="category_header">{{ __('admin_payment_edit.header')}}</h4></th>
    </tr>
  </thead>
  <tbody>
     <input type="hidden" name="type" value="edit_data" />
      <tr>
        <td>
            <div class="form-row">
            <div class="form-group col-md-6">
                  <label for="inputAddress2">{{ __('admin_payment_edit.name')}}</label>
                  <input type="text" class="form-control" name="payment_name" value="{{ $payment->pay_t_name }}">
                  @if ($errors->has('payment_name'))
                    <div class="error">
                        {{ $errors->first('payment_name') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">{{ __('admin_payment_edit.available')}}</label>
                    <select class="form-control" name="payment_exst">
                        @if($payment->pay_t_exst)
                            <option value="1" selected>{{ __('admin_payment_edit.yes')}}</option>
                            <option value="0">{{ __('admin_payment_edit.no')}}</option>
                        @elseif($payment->pay_t_exst == 0)
                            <option value="1">{{ __('admin_payment_edit.yes')}}</option>
                            <option value="0" selected>{{ __('admin_payment_edit.no')}}</option>
                        @else
                            <option value="1">{{ __('admin_payment_edit.yes')}}</option>
                            <option value="0">{{ __('admin_payment_edit.no')}}</option>
                        @endif
                    </select>
                </div>
                <hr>
                <input class="btn btn-primary w-100" type="submit" value="{{ __('admin_payment_edit.update')}}" />
            </div>
        </td>
    </tr>
  </tbody>
</table>
</form>
</div>
@endsection
@section('footer')
@parent
@endsection
