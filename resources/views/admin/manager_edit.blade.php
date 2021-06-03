@extends('admin.layouts.master')
@section('title', __('admin_manager_edit.title'))
@section('content')
<div class="container">
<form action="{{ route('admin.manager.update', ['id' => $manager->manager_id]) }}" method="POST">
@method('PUT')
@csrf
<table class="table table-hover data_small_table">
  <thead>
    <tr>
      <th scope="col" class="h_center">{{ __('admin_manager_edit.header')}}</th>
    </tr>
  </thead>
  <tbody>
     <input type="hidden" name="type" value="edit_data" />
      <tr>
        <td>
        <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="inputAddress2">{{ __('admin_manager_edit.f_name')}}</label>
                  <input type="text" class="form-control @error('f_name') is-invalid @enderror" required name="f_name" value="{{ $manager->manager_fname}}">
                  @if ($errors->has('f_name'))
                    <div class="error">
                        {{ $errors->first('f_name') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-4">
                  <label for="inputAddress2">{{ __('admin_manager_edit.l_name')}}</label>
                  <input type="text" class="form-control @error('l_name') is-invalid @enderror" required name="l_name" value="{{ $manager->manager_lname }}">
                  @if ($errors->has('l_name'))
                    <div class="error">
                        {{ $errors->first('l_name') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-4">
                  <label for="inputAddress2">{{ __('admin_manager_edit.m_name')}}</label>
                  <input type="text" class="form-control @error('m_name') is-invalid @enderror" required name="m_name" value="{{ $manager->manager_mname }}">
                  @if ($errors->has('m_name'))
                    <div class="error">
                        {{ $errors->first('m_name') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-4">
                  <label for="inputAddress2">{{ __('admin_manager_edit.phone')}}</label>
                  <input type="number" class="form-control @error('phone') is-invalid @enderror" required name="phone" value="{{ $manager->manager_phone }}">
                  @if ($errors->has('phone'))
                    <div class="error">
                        {{ $errors->first('phone') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-4">
                  <label for="inputAddress2">{{ __('admin_manager_edit.email')}}</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" required name="email" value="{{ $manager->email }}">
                  @if ($errors->has('email'))
                    <div class="error">
                        {{ $errors->first('email') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-8">
                  <label for="inputAddress2">{{ __('admin_manager_edit.password')}}</label>
                  <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="new password" value="">
                  @if ($errors->has('password'))
                    <div class="error">
                        {{ $errors->first('password') }}
                    </div>
                  @endif
                </div>
              </form>
              <hr>
              <button  class="btn btn-primary w-100" type="submit" >{{ __('admin_manager_edit.update')}}</button>
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
