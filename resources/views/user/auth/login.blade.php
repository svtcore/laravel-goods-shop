@extends('user.layouts.master')
@section('title', __('user_login.title'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('user_login.login') }}</div>
                <div class="card-body">
                    @error('login_status')
                    <div class="alert alert-danger" id="user_auth_error_form" role="alert">
                        {{ $message }}
                    </div>
                    @enderror
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="user_phone" class="col-md-4 col-form-label text-md-right">{{ __('user_login.phone') }}</label>

                            <div class="col-md-6">
                                <input id="user_phone" type="text" class="form-control @error('user_phone') is-invalid @enderror" name="user_phone" value="{{ old('user_phone') }}" required autocomplete="user_phone" autofocus>
                                @error('user_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('user_login.password') }}</label>

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
                                        {{ __('user_login.remember') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary form-control">
                                    {{ __('user_login.login_button') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('user_login.forgot') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="form-control">
                        <a href="{{ route('manager.login.page') }}" class="float-left">{{ __('user_login.manager')}}</a>
                        <a href="{{ route('admin.login.page') }}" class="float-right">{{ __('user_login.admin')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
