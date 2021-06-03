@extends('admin.layouts.master')
@section('title', __('admin_user_edit.title'))
@section('content')
<div class="container">
<h4 class="text-center">{{ __('admin_user_edit.header')}}</h4>
<table class="table data_small_table">
  <tbody>
  <form  action="{{ route('admin.user.update',['id' => $user->user_id]) }}" method="POST">
      @method('PUT')
      @csrf
     <input type="hidden" name="type" value="edit_data" />
      <tr>
      <td class="data_customer_info">
        <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputAddress2">{{ __('admin_user_edit.f_name')}}</label>
                  <input type="text" class="form-control" name="f_name" value="{{ $user->user_fname }}">
                  @if ($errors->has('f_name'))
                    <div class="error">
                        {{ $errors->first('f_name') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label for="inputAddress2">{{ __('admin_user_edit.l_name')}}</label>
                  <input type="text" class="form-control" name="l_name" value="{{ $user->user_lname }}">
                  @if ($errors->has('l_name'))
                    <div class="error">
                        {{ $errors->first('l_name') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label for="inputAddress2">{{ __('admin_user_edit.phone')}}</label>
                  <input type="text" class="form-control" name="phone" value="{{ $user->user_phone }}">
                  @if ($errors->has('phone'))
                    <div class="error">
                        {{ $errors->first('phone') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label for="inputAddress2">{{ __('admin_user_edit.email')}}</label>
                  <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                  @if ($errors->has('email'))
                    <div class="error">
                        {{ $errors->first('email') }}
                    </div>
                  @endif
                </div>
              <hr>
              <button class="btn btn-primary w-100" type="submit" >{{ __('admin_user_edit.update')}}</button>
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
