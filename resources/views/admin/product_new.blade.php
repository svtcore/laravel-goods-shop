@extends('admin.layouts.master')
@section('title', __('admin_product_new.title'))
@section('content')
<h4 class="category_header">{{ __('admin_product_new.header')}}</h4>
<div class="container">
<form action="{{ route('admin.product.add') }}" method="POST" enctype="multipart/form-data">
@csrf
<table class="table">
  <thead>
    <tr>
      <th scope="col" class="w-50">{{ __('admin_product_new.product_image')}}</th>
      <th scope="col" class="w-50">{{ __('admin_product_new.information')}}</th>
    </tr>
  </thead>
  <tbody>
     <input type="hidden" name="type" value="edit_data" />
      <tr>
        <td>
                <div class="admin_product_image_block">
                    <div class="admin_product_block">
                        <img class="admin_product_img" id="image_1" src="{{  asset('storage/assets/img/products/no-image.jpg') }}"></img>
                    </div>
                    <div class="upload_file_block">
                        <div class="form-group">
                            <input type="hidden" name="default_1" id="default_1" value="-1" />
                            <input type="file" name="file_image_1" id="file_image_1" class="form-control-file" onchange="readURL(this,1);">
                        </div>
                        <input type="button" onclick="resetUploadButton(1)" class="btn btn-primary w-100" value="{{ __('admin_product_new.default')}}">
                    @if ($errors->has('file_image_1'))
                        <div class="error">
                            {{ $errors->first('file_image_1') }}
                        </div>
                    @endif
                    </div>
                    </div>
                </div>
                <hr>
                <div class="admin_product_image_block">
                    <div class="admin_product_block">
                        <img class="admin_product_img" id="image_2" src="{{ asset('storage/assets/img/products/no-image.jpg') }}"></img>
                    </div>
                    <div class="upload_file_block">
                        <div class="form-group">
                            <input type="hidden" name="default_2" id="default_2" value="-1" />
                            <input type="file" name="file_image_2" id="file_image_2" class="form-control-file" onchange="readURL(this,2);">
                        </div>
                        <input onclick="resetUploadButton(2)" class="btn btn-primary w-100" value="{{ __('admin_product_new.default')}}">
                        @if ($errors->has('file_image_2'))
                        <div class="error">
                            {{ $errors->first('file_image_2') }}
                        </div>
                        @endif
                    </div>
                    </div>
                </div>
                <hr>
                <div class="admin_product_image_block">
                    <div class="admin_product_block">
                        <img class="admin_product_img" id="image_3" src="{{  asset('storage/assets/img/products/no-image.jpg') }}"></img>
                    </div>
                    <div class="upload_file_block">
                        <div class="form-group">
                            <input type="hidden" name="default_3" id="default_3" value="-1" />
                            <input type="file" name="file_image_3" id="file_image_3" class="form-control-file" onchange="readURL(this,3);">
                        </div>
                        <input onclick="resetUploadButton(3)" class="btn btn-primary w-100" value="{{ __('admin_product_new.default')}}"/>
                        @if ($errors->has('file_image_3'))
                        <div class="error">
                            {{ $errors->first('file_image_3') }}
                        </div>
                        @endif    
                    </div>
                    </div>
                </div>
                <hr>
                <div class="admin_product_image_block">
                    <div class="admin_product_block">
                        <img class="admin_product_img" id="image_4" src="{{  asset('storage/assets/img/products/no-image.jpg') }}"></img>
                    </div>
                    <div class="upload_file_block">
                        <div class="form-group">
                            <input type="hidden" name="default_4" id="default_4" value="-1" />
                            <input type="file" name="file_image_4" id="file_image_4" class="form-control-file" onchange="readURL(this,4);" id="exampleFormControlFile1">
                        </div>
                        <input onclick="resetUploadButton(4)" class="btn btn-primary w-100 reset_upload_btn" value="{{ __('admin_product_new.default')}}">
                        @if ($errors->has('file_image_4'))
                        <div class="error">
                            {{ $errors->first('file_image_4') }}
                        </div>
                        @endif    
                    </div>
                    </div>
                </div>
                <hr>
        </td>
        <td>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('admin_product_new.info')}}</a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">English</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Deutsch</a>
                <a class="nav-item nav-link" id="nav-lang-3-tab" data-toggle="tab" href="#nav-lang-3" role="tab" aria-controls="nav-lang-3" aria-selected="false">Українська</a>
                <a class="nav-item nav-link" id="nav-lang-4-tab" data-toggle="tab" href="#nav-lang-4" role="tab" aria-controls="nav-lang-4" aria-selected="false">Русский</a>
            </div>
        </nav>
            <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <br />
            <br />
            <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="inputAddress2">{{ __('admin_product_new.price')}}</label>
                  <input type="number" class="form-control" name="product_price" value="">
                  @if ($errors->has('product_price'))
                    <div class="error">
                        {{ $errors->first('product_price') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <label for="inputAddress2">{{ __('admin_product_new.weight')}}</label>
                  <input type="number" step="any" min="0.01" max="10000" class="form-control" name="product_weight" value="">
                  @if ($errors->has('product_weight'))
                    <div class="error">
                        {{ $errors->first('product_weight') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label for="inputState">{{ __('admin_product_new.category')}}</label>
                  {!! Form::select('size', $catg, 1, ['name' => 'product_categ', 'class'=>'form-control']); !!}
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_new.available')}}</label>
                    <select class="form-control" name="product_exst">
                        <option value="1" selected >{{ __('admin_product_new.yes')}}</option>
                        <option value="0">{{ __('admin_product_new.no')}}</option>
                    </select>
                </div>
              </div>
              <hr>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_product_new.name')}}</label>
                  <input type="text" class="form-control" name="product_name_en" placeholder="Optional" value="">
                  @if ($errors->has('product_name_en'))
                    <div class="error">
                        {{ $errors->first('product_name_en') }}
                    </div>
                  @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_new.description')}}</label>
                    <textarea class="form-control" name="product_description_en" placeholder="Optional" rows="3"></textarea>
                </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_product_new.name')}}</label>
                  <input type="text" class="form-control" name="product_name_de" placeholder="Optional" value="">
                  @if ($errors->has('product_name_de'))
                    <div class="error">
                        {{ $errors->first('product_name_de') }}
                    </div>
                  @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_new.description')}}</label>
                    <textarea class="form-control" name="product_description_de" placeholder="Optional" rows="3"></textarea>
                </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-lang-3" role="tabpanel" aria-labelledby="nav-lang-3-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_product_new.name')}}</label>
                  <input type="text" class="form-control" name="product_name_uk" placeholder="Optional" value="">
                  @if ($errors->has('product_name_uk'))
                    <div class="error">
                        {{ $errors->first('product_name_de') }}
                    </div>
                  @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_new.description')}}</label>
                    <textarea class="form-control" name="product_description_uk" placeholder="Optional" rows="3"></textarea>
                </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-lang-4" role="tabpanel" aria-labelledby="nav-lang-4-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_product_new.name')}}</label>
                  <input type="text" class="form-control" placeholder="Optional" name="product_name_ru" value="">
                  @if ($errors->has('product_name_ru'))
                    <div class="error">
                        {{ $errors->first('product_name_ru') }}
                    </div>
                  @endif
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_new.description')}}</label>
                    <textarea class="form-control" placeholder="Optional" name="product_description_ru" rows="3"></textarea>
                </div>
              </div>
              <hr>
            </div>
            </div>
            </div>
            <button  class="btn btn-primary w-100" type="submit" >{{ __('admin_product_new.add')}}</button>
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
