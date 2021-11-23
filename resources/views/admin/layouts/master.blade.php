<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="">

  <title>@yield('title')</title>

  <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/bootstrap-select.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/app.min.scss') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/sortable-theme-bootstrap.css') }}" />
</head>

<body>

  <div class="container">
    <header class="site-header py-3">
      <div class="row flex-nowrap justify-content-between align-items-center ">
        <div class="col-4 pt-1">
          <a class="text-muted" href="#">+3(098)-12-34-56</a>
        </div>
        <div class="col-4 text-center">
          <a class="site-header-logo text-dark" href="{{ route('user.main.page') }}">Goods Shop</a>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <select class="selectpicker show-menu-arrow" onchange="location = this.value;" data-width="fit">
            <option value="{{ route('locale', ['locale' => 'en'])}}" @if (app()->getLocale() == 'en') selected @endif>English</option>
            <option value="{{ route('locale', ['locale' => 'de'])}}" @if (app()->getLocale() == 'de') selected @endif>Deutsch</option>
            <option value="{{ route('locale', ['locale' => 'uk'])}}" @if (app()->getLocale() == 'uk') selected @endif>Українська</option>
            <option value="{{ route('locale', ['locale' => 'ru'])}}" @if (app()->getLocale() == 'ru') selected @endif>Русский</option>
          </select>
        </div>
      </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-light bg-light rounded" id="mob_menu_expander">
      <button class="navbar-toggler collapsed block" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
        <div id="nav-mob">
          <span class="navbar-toggler-icon"></span>
        </div>
      </button>
      <div class="navbar-collapse collapse" id="navbarsExample09">
        <ul class="navbar-nav mr-auto">
          @guest
          <li class="nav-item active">
            <a class="nav-link" href="{{ route('user.main.page')}}">{{ __('admin_master.main') }}<span class="sr-only">(current)</span></a>
          </li>
          @endguest
          @auth('admin')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.home') }}">{{ __('admin_master.home') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.orders.index') }}">{{ __('admin_master.orders') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">{{ __('admin_master.users') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.managers.index') }}">{{ __('admin_master.managers') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.products.index') }}">{{ __('admin_master.products') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">{{ __('admin_master.categories') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.payments.index') }}">{{ __('admin_master.payment') }}</a>
          </li>
          @endauth
        </ul>
        @auth('admin')
        <form class="form-inline my-2 my-md-0">
          <a href="{{ route('admin.logout') }}" id="profile-icon">
            {{ __('admin_master.logout') }}
          </a>
        </form>
        </form>
        @endauth
        @guest
        <form class="form-inline my-2 my-md-0">
          <a href="" id="profile-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-file-person-fill" viewBox="0 0 16 16">
              <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm-1 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm-3 4c2.623 0 4.146.826 5 1.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1.245C3.854 11.825 5.377 11 8 11z" />
            </svg>
            {{ __('admin_master.login') }}
          </a>
        </form>
        @endguest
        </form>
      </div>
    </nav>
    <hr>
    </br>
    <!--<div class="jumbotron p-1 p-md-3 text-white rounded">
          <h5>Title of a longer featured blog post</h5>
      </div>-->
    </hr>

    @yield('content')
    @section('footer')
    <footer class="site-footer">
      <p><a href="https://github.com/svtcore">{{ __('user_master.copyright') }}</a></p>
      <p>
        <a href="#">{{ __('user_master.back') }}</a>
      </p>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ mix('css/app.css') }}" type="text/javascript"></script>
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/holder.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/sortable.min.js') }} "></script>
    <script src="{{ asset('assets/js/experemental.js') }}"></script>
</body>

</html>
@show