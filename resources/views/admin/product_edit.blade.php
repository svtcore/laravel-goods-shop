@extends('admin.layouts.master')
@section('title', __('admin_product_edit.title'))
@section('content')
<h4 class="category_header">{{ __('admin_product_edit.header')}}</h4>
<div class="container">
<table class="table">
  <thead>
    <tr>
      <th scope="col" class="w-50">{{ __('admin_product_edit.product_image')}}</th>
      <th scope="col" class="w-50">{{ __('admin_product_edit.information')}}</th>
    </tr>
  </thead>
  <tbody>
    <form action="{{ route('admin.product.update',['id' => $product->product_id]) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
     <input type="hidden" name="type" value="edit_data" />
      <tr>
        <td>
            @for ($i = 1; $i < 5; $i++)
                <div class="admin_product_image_block">
                    <div class="admin_product_block">
                        @isset($product->images[$i-1])
                            @if ($product->images[$i-1]->product_image_name != null)
                                <img class="admin_product_img" id="image_{{ $i }}" src="{{ asset('storage/assets/img/products')}}/{{$product->images[$i-1]->product_image_name}}"></img>
                            @else
                                <img class="admin_product_img" id="image_{{ $i }}" src="{{ asset('storage/assets/img/products/no-image.jpg')}}"></img>
                            @endif 
                        @else
                        <img class="admin_product_img" id="image_{{$i}}" src="{{ asset('storage/assets/img/products/no-image.jpg')}}"></img>
                        @endisset
                    </div>
                    <div class="upload_file_block">
                        <div class="form-group">
                            <input type="hidden" name="default_{{$i}}" id="default_{{$i}}" value="-1" />
                            <input type="file" name="file_image_{{$i}}" id="file_image_{{$i}}" class="form-control-file" onchange="readURL(this,{{$i}});">
                        </div>
                        <input type="button" onclick="resetUploadButton({{$i}})" class="btn btn-primary w-100" value="{{ __('admin_product_edit.default')}}">
                    @if ($errors->has('file_image_$i'))
                        <div class="error">
                            {{ $errors->first('file_image_$i') }}
                        </div>
                    @endif
                    </div>
                    </div>
                </div>
                <hr>
            @endfor
        </td>
        <td>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('admin_product_edit.info')}}</a>
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
                  <label for="inputAddress2">{{ __('admin_product_edit.price')}}</label>
                  <input type="number" class="form-control" name="product_price" value="{{ $product->product_price }}">
                  @if ($errors->has('product_price'))
                    <div class="error">
                        {{ $errors->first('product_price') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-3">
                  <label for="inputAddress2">{{ __('admin_product_edit.weight')}}</label>
                  <input type="number" step="any" min="0.01" max="10000" class="form-control" name="product_weight" value="{{ $product->product_weight }}">
                  @if ($errors->has('product_weight'))
                    <div class="error">
                        {{ $errors->first('product_weight') }}
                    </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label for="inputState">{{ __('admin_product_edit.category')}}</label>
                  {!! Form::select('size', $catg, $product->f_catg_id, ['name' => 'product_categ', 'class'=>'form-control']); !!}
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_edit.available')}}</label>
                    <select class="form-control" name="product_exst">
                        <option value="1" @if ($product->product_exst == 1) selected @endif >{{ __('admin_product_edit.yes')}}</option>
                        <option value="0" @if ($product->product_exst == 0) selected @endif >{{ __('admin_product_edit.no')}}</option>
                    </select>
                </div>
              </div>
              <hr>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_product_edit.name')}}</label>
                  <input type="text" class="form-control" name="product_name_en" placeholder="English name" value="{{ $product->names->product_name_lang_en }}">
                  @if ($errors->has('product_name_en'))
                    <div class="error">
                        {{ $errors->first('product_name_en') }}
                    </div>
                  @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_edit.description')}}</label>
                    <textarea class="form-control" name="product_description_en" placeholder="Optional" rows="3">{{ $product->descriptions->product_desc_lang_en }}</textarea>
                    @if ($errors->has('product_description_en'))
                    <div class="error">
                        {{ $errors->first('product_description_en') }}
                    </div>
                  @endif
                  </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_product_edit.name')}}</label>
                  <input type="text" class="form-control" name="product_name_de" placeholder="Optional" value="{{ $product->names->product_name_lang_de }}">
                  @if ($errors->has('product_name_de'))
                    <div class="error">
                        {{ $errors->first('product_name_de') }}
                    </div>
                  @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_edit.description')}}</label>
                    <textarea class="form-control" name="product_description_de" placeholder="Optional" rows="3">{{ $product->descriptions->product_desc_lang_de }}</textarea>
                    @if ($errors->has('product_description_de'))
                    <div class="error">
                        {{ $errors->first('product_description_de') }}
                    </div>
                    @endif
                  </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-lang-3" role="tabpanel" aria-labelledby="nav-lang-3-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_product_edit.name')}}</label>
                  <input type="text" class="form-control" name="product_name_uk" placeholder="Optional" value="{{ $product->names->product_name_lang_uk }}">
                  @if ($errors->has('product_name_uk'))
                    <div class="error">
                        {{ $errors->first('product_name_de') }}
                    </div>
                  @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_edit.description')}}</label>
                    <textarea class="form-control" name="product_description_uk" placeholder="Optional" rows="3">{{ $product->descriptions->product_desc_lang_uk }}</textarea>
                    @if ($errors->has('product_description_uk'))
                    <div class="error">
                        {{ $errors->first('product_description_uk') }}
                    </div>
                    @endif
                  </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-lang-4" role="tabpanel" aria-labelledby="nav-lang-4-tab">
            <br />
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputAddress2">{{ __('admin_product_edit.name')}}</label>
                  <input type="text" class="form-control" placeholder="Optional" name="product_name_ru" value="{{ $product->names->product_name_lang_ru }}">
                  @if ($errors->has('product_name_ru'))
                    <div class="error">
                        {{ $errors->first('product_name_ru') }}
                    </div>
                  @endif
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">{{ __('admin_product_edit.description')}}</label>
                    <textarea class="form-control" placeholder="Optional" name="product_description_ru" rows="3">{{ $product->descriptions->product_desc_lang_ru }}</textarea>
                    @if ($errors->has('product_description_ru'))
                    <div class="error">
                        {{ $errors->first('product_description_ru') }}
                    </div>
                  @endif
                  </div>
              </div>
              <hr>
            </div>
            </div>
            </div>
            <button  class="btn btn-primary w-100" type="submit" >{{ __('admin_product_edit.update')}}</button>
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
<script src="{{ asset('assets/js/admin-product-upload.js') }}"></script>
<script>
    $("#file_image_1").val('');
    $("#file_image_2").val('');
    $("#file_image_3").val('');
    $("#file_image_4").val('');
</script>
@endsection
