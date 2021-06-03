@extends('manager.layouts.master')
@section('title', __('manager_login.title'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('manager_login.login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('manager.login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="Manager_phone" class="col-md-4 col-form-label text-md-right">{{ __('manager_login.phone') }}</label>

                            <div class="col-md-6">
                                <input id="manager_phone" type="text" class="form-control @error('manager_phone') is-invalid @enderror" name="manager_phone" value="{{ old('manager_phone') }}" required autocomplete="email" autofocus>

                                @error('manager_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('manager_login.password') }}</label>

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
                                    {{ __('manager_login.remember') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary form-control">
                                    {{ __('manager_login.login_button') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('manager_login.forgot') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="form-control">
                        <a href="{{ route('login') }}" class="float-left">{{ __('user_login.user')}}</a>
                        <a href="{{ route('admin.login.page') }}" class="float-right">{{ __('user_login.admin')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
