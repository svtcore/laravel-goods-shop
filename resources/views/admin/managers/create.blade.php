@extends('admin.layouts.master')
@section('title', __('admin_manager_new.title'))
@section('content')
<div class="container">
  <form action="{{ route('admin.managers.store') }}" method="POST">
    @csrf
    <table class="table table-hover data_small_table">
      <thead>
        <tr>
          <th scope="col" class="h_center">{{ __('admin_manager_new.header')}}</th>
        </tr>
      </thead>
      <tbody>
        <input type="hidden" name="type" value="edit_data" />
        <tr>
          <td>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="inputAddress2">{{ __('admin_manager_new.f_name')}}</label>
                <input type="text" class="form-control @error('f_name') is-invalid @enderror" required name="f_name" value="{{ old('f_name') }}">
                @if ($errors->has('f_name'))
                <div class="error">
                  {{ $errors->first('f_name') }}
                </div>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="inputAddress2">{{ __('admin_manager_new.l_name')}}</label>
                <input type="text" class="form-control @error('l_name') is-invalid @enderror" required name="l_name" value="{{ old('l_name') }}">
                @if ($errors->has('l_name'))
                <div class="error">
                  {{ $errors->first('l_name') }}
                </div>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="inputAddress2">{{ __('admin_manager_new.m_name')}}</label>
                <input type="text" class="form-control @error('m_name') is-invalid @enderror" required name="m_name" value="{{ old('m_name') }}">
                @if ($errors->has('m_name'))
                <div class="error">
                  {{ $errors->first('m_name') }}
                </div>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="inputAddress2">{{ __('admin_manager_new.phone')}}</label>
                <input type="number" class="form-control @error('phone') is-invalid @enderror" required name="phone" value="{{ old('phone') }}">
                @if ($errors->has('phone'))
                <div class="error">
                  {{ $errors->first('phone') }}
                </div>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="inputAddress2">{{ __('admin_manager_new.email')}}</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" required name="email" value="{{ old('email') }}">
                @if ($errors->has('email'))
                <div class="error">
                  {{ $errors->first('email') }}
                </div>
                @endif
              </div>
              <div class="form-group col-md-8">
                <label for="inputAddress2">{{ __('admin_manager_new.password')}}</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" required name="password" placeholder="new password" value="{{ old('password') }}">
                @if ($errors->has('password'))
                <div class="error">
                  {{ $errors->first('password') }}
                </div>
                @endif
              </div>
  </form>
  <hr>
  <button class="btn btn-primary w-100" type="submit">{{ __('admin_manager_new.add')}}</button>
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