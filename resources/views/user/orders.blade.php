@extends('user.layouts.master')
@section('title', __('user_orders.title'))
@section('content')
<h4 class="category_header">{{ __('user_orders.your_orders') }}</h4>
<br />
<div class="container">
<table class="table table-hover sortable-theme-ligh" data-sortable>
  <thead>
    <tr>
      <th scope="col">{{ __('user_orders.order_num_tb') }}</th>
      <th scope="col">{{ __('user_orders.date_tb') }}</th>
      <th scope="col">{{ __('user_orders.info_tb') }}</th>
      <th scope="col">{{ __('user_orders.ordered_tb') }}</th>
      <th scope="col">{{ __('user_orders.total_price_tb') }}</th>
      <th scope="col">{{ __('user_orders.payment_tb') }}</th>
      <th scope="col">{{ __('user_orders.status_tb') }}</th>
    </tr>
  </thead>
  <tbody>
  @if(!empty($orders))
      @foreach ($orders as $index => $order)
      @isset($order->order_id)
      <tr>
        <td class="text-center" scope="row">{{ $order->order_id }}</td>
        <td class="text-center">
            @isset($order->created_at)
                {{ $order->created_at }}
            @endisset
        </td>
        <td>
        <button class="btn btn-success w-100 user_orders_info_block" type="button" data-toggle="collapse" data-target="#collapse_{{ $order->order_id }}" aria-expanded="false" aria-controls="collapse_{{ $order->order_id }}">
                {{ __('user_orders.show_details') }}
            </button>
            </br>
            <div class="list-group w-100 collapse" id="collapse_{{ $order->order_id }}">
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-start active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('user_orders.name') }}</h5>
                    </div>
                    <p class="mb-1">
                        @isset($order->order_fname)
                            {{ $order->order_fname }} {{ $order->order_lname }}
                        @endisset
                    </p>
                </span>
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-start active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('user_orders.phone') }}</h5>
                    </div>
                    <p class="mb-1">
                        @isset($order->order_phone)
                            {{ $order->order_phone }}
                        @endisset
                    </p>
                </span>
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-center active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('user_orders.address') }}</h5>
                    </div>
                    <p class="mb-1">
                        @isset($order->user_addresses->user_str_name)
                            {{ $order->user_addresses->user_str_name }} {{ __('user_master.str_label') }}, {{ __('user_master.house_label') }} {{ $order->user_addresses->user_house_num }}, {{ __('user_master.ent_label') }}{{ $order->user_addresses->user_ent_num }}, {{ __('user_master.apart_label') }} {{ $order->user_addresses->user_apart_num }}
                        @endisset
                     </p>
                </span>
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-center active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('user_orders.code') }} </h5>
                    </div>
                    <p class="mb-1">
                        @isset($order->order_code)
                            {{ $order->order_code }}
                        @endisset
                    </p>
                </span>
                <span class="list-group-item list-group-item-action flex-column w-100 align-items-center active">
                    <div class="d-flex w-50 justify-content-between">
                        <h5 class="mb-1">{{ __('user_orders.note') }}</h5>
                    </div>
                    <p class="mb-1">
                        @isset($order->order_note)
                            {{ $order->order_note }}
                        @endisset
                    </p>
                </span>
            </div>
        </td>
        <td>
            @foreach ($order->order_products as $oproduct)
              @if (isset($oproduct->products) && ($oproduct->products != null))
              <a href="{{ route('user.product.show', ['id' => $oproduct->products->product_id]) }}" target="_blank" class="list-group-item list-group-item-action">
                @if(isset($oproduct->products->names) && ($oproduct->products->names != null))
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
               @endif
              @endforeach
        </td>
        <td class="text-center">
            @isset($order->order_full_price)
                {{ $order->order_full_price }}
            @endisset
        </td>
        <td class="text-center">
            @isset($order->payment_types->pay_t_name)
                {{ $order->payment_types->pay_t_name }}
            @endisset
        </td>
        <td class="text-center">
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
      </tr>
      @else
        Record not found
      @endisset
      @endforeach
    @else
    <tr>
        <td colspan="7" class="text-center">
            <h5>{{ __('user_orders.not_found')}}</h5>
        </td>
    </tr>
    @endif
  </tbody>
</table>
</div>
@if (!empty($orders))
<div class="d-flex justify-content-center">
    {!! $orders->links() !!}
</div>
@endif
@endsection
