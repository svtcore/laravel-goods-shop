@extends('admin.layouts.master')
@section('title', __('admin_login.title'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('admin_login.login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="admin_phone" class="col-md-4 col-form-label text-md-right">{{ __('admin_login.phone') }}</label>

                            <div class="col-md-6">
                                <input id="admin_phone" type="text" class="form-control @error('admin_phone') is-invalid @enderror" name="admin_phone" value="{{ old('admin_phone') }}" required autocomplete="email" autofocus>

                                @error('admin_phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('admin_login.password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('admin_login.remember') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary form-control">
                                    {{ __('admin_login.login_button') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('admin_login.forgot') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="form-control">
                        <a href="{{ route('login') }}" class="float-left">{{ __('admin_login.user')}}</a>
                        <a href="{{ route('manager.login.page') }}" class="float-right">{{ __('admin_login.manager')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection