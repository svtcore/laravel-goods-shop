@extends('admin.layouts.master')
@section('title',  __('admin_category_new.title'))
@section('content')
<div class="container">
<form action="{{ route('admin.category.add') }}" method="POST">
@csrf
<table class="table data_small_table">
  <thead>
    <tr>
      <th scope="col"><h4 class="category_header">{{ __('admin_category_new.header') }}</h4></th>
    </tr>
  </thead>
  <tbody>
     <input type="hidden" name="type" value="edit_data" />
      <tr>
        <td>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">English</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Deutsch</a>
                <a class="nav-item nav-link" id="nav-lang-3-tab" data-toggle="tab" href="#nav-lang-3" role="tab" aria-controls="nav-lang-3" aria-selected="false">Ukranian</a>
                <a class="nav-item nav-link" id="nav-lang-4-tab" data-toggle="tab" href="#nav-lang-4" role="tab" aria-controls="nav-lang-4" aria-selected="false">Russian</a>
            </div>
        </nav>
            <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_category_new.name') }}</label>
                  <input type="text" class="form-control" name="category_name_en" placeholder="English name" value=""/>
                  @if ($errors->has('category_name_en'))
                    <div class="error">
                        {{ $errors->first('category_name_en') }}
                    </div>
                  @endif
                </div>
            </div>
            <hr>
            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_category_new.name') }}</label>
                  <input type="text" class="form-control" name="category_name_de" placeholder="Deutsch name" value="">
                  @if ($errors->has('category_name_de'))
                    <div class="error">
                        {{ $errors->first('category_name_de') }}
                    </div>
                  @endif
                </div>
            </div>
            <hr>
            </div>
            <div class="tab-pane fade" id="nav-lang-3" role="tabpanel" aria-labelledby="nav-lang-3-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_category_new.name') }}</label>
                  <input type="text" class="form-control" placeholder="назва українською" name="category_name_uk" value="">
                  @if ($errors->has('category_name_uk'))
                    <div class="error">
                        {{ $errors->first('category_name_uk') }}
                    </div>
                  @endif
                </div>
            </div>
              <hr>
            </div>
            <div class="tab-pane fade" id="nav-lang-4" role="tabpanel" aria-labelledby="nav-lang-4-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_category_new.name') }}</label>
                  <input type="text" class="form-control" placeholder="название на русском" name="category_name_ru" value="">
                  @if ($errors->has('category_name_ru'))
                    <div class="error">
                        {{ $errors->first('category_name_ru') }}
                    </div>
                  @endif
                </div>
            </div>
              <hr>
            </div>
            </div>
            </div>
            <button  class="btn btn-primary w-100" type="submit" >{{ __('admin_category_new.add') }}</button>
        </td>
    </tr>
  </tbody>
</table>
</form>
</div>
@endsection
@section('footer')
@parent
<script src="{{ asset('assets/js/admin-product-upload.js') }}"></script>
@endsection
