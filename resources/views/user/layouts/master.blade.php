<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.min.scss') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/sortable-theme-bootstrap.css') }}" />
    <script src="js/app.js"></script> 
  </head>

  <body>

    <div class="container">
    <header class="site-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center ">
          <div class="col-4 pt-1">
            <a class="text-muted" href="#">+38(098)-12-34-56</a>
          </div>
          <div class="col-4 text-center">
            <a class="site-header-logo text-dark" href="{{route('user.main.page') }}">Goods Shop</a>
          </div>
          <div class="col-4 d-flex justify-content-end align-items-center">
            @auth('manager')
              <a href="{{ route('manager.orders.status', ['name' => 'created']) }}" class="btn btn-outline-success" role="button" aria-pressed="true">{{ __('user_master.back_to_manage') }}</a>
            @endauth
            @auth('admin')
              <a href="{{ route('admin.home') }}" class="btn btn-outline-success" role="button" aria-pressed="true">{{ __('user_master.back_to_manage') }}</a>
            @endauth
            <select class="selectpicker show-menu-arrow" onchange="location = this.value;" data-width="fit">
                <option value="{{ route('locale', ['locale' => 'en'])}}" @if (app()->getLocale() == 'en') selected @endif>English</option>
                <option value="{{ route('locale', ['locale' => 'de'])}}" @if (app()->getLocale() == 'de') selected @endif>Deutsch</option>
                <option value="{{ route('locale', ['locale' => 'uk'])}}" @if (app()->getLocale() == 'uk') selected @endif>Українська</option>
                <option value="{{ route('locale', ['locale' => 'ru'])}}" @if (app()->getLocale() == 'ru') selected @endif>Русский</option>
            </select>
          </div>
        </div>
      </header>

      <nav class="first_nav">
      <div class="first_nav_block">
          <div id="app">
            <search-product-component></search-product-component>
          </div>
          <div class="float-right">
          <a href="{{ route('user.order.cart') }}" class="ml-2" id="cart_icon"> 
              <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
              </svg>
              <span class="badge rounded-pill badge-notification bg-warning" id="cart_count"></span>
          </a>
          </div>
          @auth('web')
          <div class="float-right">
            <select class="selectpicker show-menu-arrow" onchange="location = this.value;" data-width="fit">
                  <option selected>{{ __('user_master.my_account') }}</option>
                  <option value="{{ route('user.orders') }}">{{ __('user_master.my_orders') }}</option>
                  <option value="{{ route('user.logout') }}">{{ __('user_master.logout') }}</option></a>
            </select>
          </div>
          @endauth
          @guest
          <div class="float-right">
          <a href="{{ route('login') }}" id="profile-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-file-person-fill" viewBox="0 0 16 16">
              <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm-1 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm-3 4c2.623 0 4.146.826 5 1.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1.245C3.854 11.825 5.377 11 8 11z"/>
            </svg>
            {{ __('user_master.login') }}
          </a>
          </div>
          @endguest
      </div>
      </nav>
      <br />
      <hr>
      <nav class="navbar navbar-expand-lg navbar-light bg-light rounded" id="mob_menu_expander">
        <button class="navbar-toggler collapsed block" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
        <div id="nav-mob">  
          <span class="navbar-toggler-icon">Menu</span>
        </div>
        </button>
        <div class="navbar-collapse collapse" id="navbarsExample09">
          <ul class="navbar-nav nav_center" >
            <li class="nav-item active">
              <a class="nav-link" href="{{ route('user.main.page') }}">{{ __('user_master.main') }}<span class="sr-only">(current)</span></a>
            </li>
            @foreach ($categories as $index => $categ)
            @if(app()->getLocale() == "en")
              <li class="nav-item">
                <a class="nav-link" href="{{ route('user.category.show', ['id' => $categ->catg_id]) }}">{{ $categ->catg_name_en }}</a>
              </li>
            @elseif(app()->getLocale() == "de")
              <li class="nav-item">
                <a class="nav-link" href="{{ route('user.category.show', ['id' => $categ->catg_id]) }}">{{ $categ->catg_name_de }}</a>
              </li>
            @elseif(app()->getLocale() == "uk")
              <li class="nav-item">
                <a class="nav-link" href="{{ route('user.category.show', ['id' => $categ->catg_id]) }}">{{ $categ->catg_name_uk }}</a>
              </li>
            @elseif(app()->getLocale() == "ru")
              <li class="nav-item">
                <a class="nav-link" href="{{ route('user.category.show', ['id' => $categ->catg_id]) }}">{{ $categ->catg_name_ru }}</a>
              </li>
            @endif
            @endforeach
            <!--<li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
              <div class="dropdown-menu" aria-labelledby="dropdown09">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </li>-->
          </ul>
        </div>
        <div class="float-left">
      </div>
      </nav>
      <hr>


@yield('content')
@section('footer')
    <footer class="site-footer">
      <p><a href="https://github.com/svtcore">{{ __('user_master.copyright') }}</a></p>
      <p>
        <a href="#">{{ __('user_master.back') }}</a>
      </p>
    </footer>
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ mix('css/app.css') }}" type="text/javascript"></script>
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/holder.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/js/sortable.min.js') }} "></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script>
      $('#input_search').ready(function() {
        $("#input_search").attr("placeholder", "{{ __('admin_products.search')}}");
      });
    </script>
   </body>
</html>
@show