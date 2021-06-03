@extends('admin.layouts.master')
@section('title', __('admin_payment_new.title'))
@section('content')
<div class="container">
<form action="{{ route('admin.payment.add') }}" method="POST">
@csrf
<table class="table data_table">
  <thead>
    <tr>
      <th scope="col"><h4 class="category_header">{{ __('admin_payment_new.header')}}</h4></th>
    </tr>
  </thead>
  <tbody>
     <input type="hidden" name="type" value="edit_data" />
      <tr>
        <td>
            <div class="form-row">
            <div class="form-group col-md-6">
                  <label for="inputAddress2">{{ __('admin_payment_new.name')}}</label>
                  <input type="text" class="form-control" name="payment_name" value="">
                  @if ($errors->has('payment_name'))
                    <div class="error">
                        {{ $errors->first('payment_name') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">{{ __('admin_payment_new.available')}}</label>
                    <select class="form-control" name="payment_exst">
                            <option value="1">{{ __('admin_payment_new.yes')}}</option>
                            <option value="0">{{ __('admin_payment_new.no')}}</option>
                    </select>
                </div>
                <hr>
                <input class="btn btn-primary w-100" type="submit" value="{{ __('admin_payment_new.add')}}" />
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
