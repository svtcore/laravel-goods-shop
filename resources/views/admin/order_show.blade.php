@extends('admin.layouts.master')
@section('title', __('admin_order_show.title'))
@section('content')
<h4 class="category_header">
    {{ __('admin_order_show.title')}}
</h4>
<div class="container">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div id="app">
    <orders-search-component></orders-search-component>
</div>
<br />
<table class="table">
  <thead>
    <tr>
      <th scope="col" class="small-column">{{ __('admin_order_show.order_num_tb')}}</th>
      <th scope="col" class="small-column">{{ __('admin_order_show.date_tb')}}</th>
      <th scope="col" class="w-25">{{ __('admin_order_show.customer_info_tb')}}</th>
      <th scope="col" class="w-25">{{ __('admin_order_show.ordered_tb')}}</th>
      <th scope="col" class="small-column">{{ __('admin_order_show.price_tb')}}</th>
      <th scope="col" class="small-column">{{ __('admin_order_show.payment_tb')}}</th>
      <th scope="col" class="small-column">{{ __('admin_order_show.status_tb')}}</th>
      <th scope="col" class="small-column">{{ __('admin_order_show.action_tb')}}</th>
    </tr>
  </thead>
  <tbody id="content-data">
    @if(isset($order->order_id))
            <tr>
            <th scope="row">{{ $order->order_id }}</th>
            <td>
                @isset($order->created_at)
                    {{ $order->created_at }}
                @endisset
            </td>
            <td>
            <div class="list-group w-100 user_orders_info_block"  d="collapse_{{ $order->order_id }}">
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-start active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('admin_order_show.name')}}</h5>
                    </div>
                    @isset($order->order_fname)
                        <p class="mb-1">{{ $order->order_fname }} {{ $order->order_lname }}</p>
                    @endisset
                </span>
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-start active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('admin_order_show.phone') }}</h5>
                    </div>
                    @isset($order->order_phone)
                    <p class="mb-1">{{ $order->order_phone }}</p>
                    @endisset
                </span>
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-center active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('admin_order_show.code')}}</h5>
                    </div>
                    @isset($order->order_code)
                    <p class="mb-1">{{ $order->order_code }}</p>
                    @endisset
                </span>
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-center active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('admin_order_show.address')}}</h5>
                    </div>
                    @isset($order->user_addresses->user_str_name)
                    <p class="mb-1">{{ $order->user_addresses->user_str_name }} {{ __('user_master.str_label') }}, {{ __('user_master.house_label') }} {{ $order->user_addresses->user_house_num }}, {{ __('user_master.ent_label') }}{{ $order->user_addresses->user_ent_num }}, {{ __('user_master.apart_label') }} {{ $order->user_addresses->user_apart_num }}</p>
                    @endisset
                </span>
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-center active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('admin_order_show.note') }}</h5>
                    </div>
                    @isset($order->order_note)
                    <p class="mb-1">{{ $order->order_note }}</p>
                    @endisset
                </span>
            </div>
            </td>
            <td>
                <div class="list-group">
                @foreach ($order->order_products as $oproduct)
                @if (isset($oproduct->products) && ($oproduct->products != null))
                <a href="{{ route('user.product.show', ['id' => $oproduct->products->product_id]) }}" target="_blank" class="list-group-item list-group-item-action">
                    @if (app()->getLocale() == "en") 
                        {{ $oproduct->products->names->product_name_lang_en }}
                    @elseif (app()->getLocale() == "de")
                        {{ $oproduct->products->names->product_name_lang_de }}
                    @elseif (app()->getLocale() == "uk")
                        {{ $oproduct->products->names->product_name_lang_uk }}
                    @elseif (app()->getLocale() == "ru")
                        {{ $oproduct->products->names->product_name_lang_ru }}
                    @endif
                    x {{ $oproduct->order_p_count}}</a>
                @endif
                @endforeach
            </td>
            <td>
            @isset($order->order_full_price)
                {{ $order->order_full_price }}
            @endisset
            </td>
            @if (isset($order->payment_types))
                @if(isset($order->payment_types->deleted_at) && ($order->payment_types->deleted_at != null))
                    <td class="c_grey">
                        {{ $order->payment_types->pay_t_name }}
                    </td>
                @else
                    <td>
                        {{ $order->payment_types->pay_t_name }}
                    </td>
                @endif
            @endif
            </td>
                <td>
                    @isset($order->order_status)
                        @if (app()->getLocale() == "en")
                            @if ($order->order_status == "created") Created
                            @elseif ($order->order_status == "processing") Processing
                            @elseif ($order->order_status == "completed") Completed
                            @elseif ($order->order_status == "canceled") Canceled
                            @else {{ $order->order_status }}
                            @endif
                        @elseif (app()->getLocale() == "de")
                            @if ($order-> order_status == "created") Erstellt
                            @elseif ($order->order_status == "processing") Verarbeitung
                            @elseif ($order->order_status == "completed") Abgeschlossen
                            @elseif ($order->order_status == "canceled") Storniert
                            @else {{$order->order_status}}
                            @endif
                        @elseif (app()->getLocale() == "uk")
                            @if ($order->order_status == "created") Створено
                            @elseif ($order->order_status == "processing") Оброблється
                            @elseif ($order->order_status == "completed") Виконано
                            @elseif ($order->order_status == "canceled") Відмінено
                            @else {{ $order->order_status }}
                            @endif
                        @elseif (app()->getLocale() == "ru")
                            @if ($order->order_status == "created") Создано
                            @elseif ($order->order_status == "processing") Обрабатывается
                            @elseif ($order->order_status == "completed") Выполнено
                            @elseif ($order->order_status == "canceled") Отменено
                            @else {{ $order->order_status }}
                            @endif
                        @else Null
                        @endif
                @endisset   
                </td>
                <td>
                        <form class="form-group w-100" action="{{ route('admin.order.update', ['id' => $order->order_id ]) }}" method="POST">
                            @method('PUT')
                            @csrf
                                <input type="hidden" name="type" value="created"/>
                                <input class="btn btn-primary w-100" name="submit" type="submit" value="{{ __('admin_order_show.created') }}">
                        </form>
                        <form class="form-group w-100" action="{{ route('admin.order.update', ['id' => $order->order_id ]) }}" method="POST">
                            @method('PUT')
                            @csrf
                                <input type="hidden" name="type" value="confirm"/>
                                <input class="btn btn-primary w-100" name="submit" type="submit" value="{{ __('admin_order_show.processing') }}">
                        </form>
                        <form class="form-group w-100" action="{{ route('admin.order.update', ['id' => $order->order_id ]) }}" method="POST">
                            @method('PUT')
                            @csrf
                                <input type="hidden" name="type" value="complete"/>
                                <input class="btn btn-primary w-100" name="submit" type="submit" value="{{ __('admin_order_show.completed') }}">
                        </form>
                        <form class="form-group w-100" action="{{ route('admin.order.update', ['id' => $order->order_id ]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="type" value="cancel"/>
                            <input class="btn btn-primary w-100" name="submit" type="submit" value="{{ __('admin_order_show.canceled') }}">
                        </form>
                        <form class="form-group w-100">
                            <a class="btn btn-primary w-100" href="{{ route('admin.order.edit', ['id' => $order->order_id ]) }}" target="_blank" role="button">{{ __('admin_order_show.edit') }}</a>
                        </form>
                    </td>
            </tr>
    @else
    <tr>
        <td colspan="7" class="text-center">
        <h5>{{ __('manager_orders.not_found') }}</h5>
        </td>
    </tr>
    @endif
  </tbody>
</table>
</div>
@endsection
@section('footer')
@parent
<script>
$('#input_search').ready(function() {
    $("#input_search").attr("placeholder", "{{ __('admin_orders.search')}}");
});
</script>
@endsection
