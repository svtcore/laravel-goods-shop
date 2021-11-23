@extends('manager.layouts.master')
@section('title', __('manager_orders.title'))
@section('content')
<h4 class="category_header">
    @isset($orders[0]['order_status'])
    @if ($orders[0]['order_status'] == "created")
    {{ __('manager_orders.created_header')}}
    @elseif ($orders[0]['order_status'] == "processing")
    {{ __('manager_orders.processing_header')}}
    @elseif ($orders[0]['order_status'] == "completed")
    {{ __('manager_orders.completed_header')}}
    @endif
    @endisset
</h4>
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div id="app">
        <search-ordersm-component></search-ordersm-component>
    </div>
    <br />
    <table class="table table-hover sortable-theme-ligh" data-sortable>
        <thead>
            <tr>
                <th scope="col" class="small-column text-center">{{ __('manager_order_show.order_num_tb')}}</th>
                <th scope="col" class="w_5 text-center">{{ __('manager_order_show.date_tb')}}</th>
                <th scope="col" class="w-50 text-center">{{ __('manager_order_show.customer_info_tb')}}</th>
                <th scope="col" class="w-50 text-center">{{ __('manager_order_show.ordered_tb')}}</th>
                <th scope="col" class="small-column text-center">{{ __('manager_order_show.price_tb')}}</th>
                <th scope="col" class="small-column text-center">{{ __('manager_order_show.payment_tb')}}</th>
                @isset($orders[0]['order_status'])
                @if ($orders[0]['order_status'] != "completed")
                <th scope="col" data-sortable="false" class="small-column text-center">{{ __('manager_order_show.action_tb')}}</th>
                @endif
                @endisset
            </tr>
        </thead>
        <tbody id="content-data">
            @if (!empty($orders))
            @foreach ($orders as $index => $order)
            @isset($order->order_id)
            <tr>
                <td scope="row" class="text-center">
                    @isset($order->order_id)
                    {{ $order->order_id }}
                    @endisset
                </td>
                <td class="text-center">
                    @isset($order->created_at)
                    {{ $order->created_at }}
                    @endisset
                </td>
                <td>
                    <button class="btn btn-success w-100 orders_data_block" type="button" data-toggle="collapse" data-target="#collapse_{{ $order->order_id }}" aria-expanded="false" aria-controls="collapse_{{ $order->order_id }}">
                        {{ __('manager_orders.show_details') }}
                    </button>
                    </br>
                    <div class="list-group w-100 collapse order_data_block" id="collapse_{{ $order->order_id }}">
                        <span class="list-group-item list-group-item-action flex-column w-100 align-items-start active">
                            <div class="d-flex w-50 justify-content-between">
                                <h5 class="mb-1">{{ __('manager_orders.name') }}</h5>
                            </div>
                            @isset($order->order_fname)
                            <p class="mb-1">{{ $order->order_fname }} {{ $order->order_lname }}</p>
                            @endisset
                        </span>
                        <span class="list-group-item list-group-item-action flex-column w-100 align-items-start active">
                            <div class="d-flex w-50 justify-content-between">
                                <h5 class="mb-1">{{ __('manager_orders.phone') }}</h5>
                            </div>
                            @isset($order->order_phone)
                            <p class="mb-1">{{ $order->order_phone }}</p>
                            @endisset
                        </span>
                        <span class="list-group-item list-group-item-action flex-column w-100 align-items-center active">
                            <div class="d-flex w-50 justify-content-between">
                                <h5 class="mb-1">{{ __('manager_orders.address') }}</h5>
                            </div>
                            @isset($order->user_addresses->user_str_name)
                            <p class="mb-1">{{ $order->user_addresses->user_str_name }} {{ __('user_master.str_label') }}, {{ __('user_master.house_label') }} {{ $order->user_addresses->user_house_num }}, {{ __('user_master.ent_label') }}{{ $order->user_addresses->user_ent_num }}, {{ __('user_master.apart_label') }} {{ $order->user_addresses->user_apart_num }} </p>
                            @endisset
                        </span>
                        <span class="list-group-item list-group-item-action flex-column w-100 align-items-center active">
                            <div class="d-flex w-50 justify-content-between">
                                <h5 class="mb-1"> {{ __('manager_orders.code') }}</h5>
                            </div>
                            @isset($order->order_code)
                            <p class="mb-1">{{ $order->order_code }}</p>
                            @endisset
                        </span>
                        <span class="list-group-item list-group-item-action flex-column w-100 align-items-center active">
                            <div class="d-flex w-50 justify-content-between">
                                <h5 class="mb-1">{{ __('manager_orders.note') }}</h5>
                            </div>
                            @isset($order->order_note)
                            <p class="mb-1">{{ $order->order_note }}</p>
                            @endisset
                        </span>
                    </div>
                </td>
                <td>
                    @foreach ($order->order_products as $oproduct)
                    @if (isset($oproduct->products) && ($oproduct->products != null))
                    <a href="{{ route('user.products.show', ['id' => $oproduct->products->product_id]) }}" target="_blank" class="list-group-item list-group-item-action">
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
                    @if ($order->order_status != "completed")
                    <div class="btn-group-vertical w-100">
                        <form class="form-group w-100" action="{{ route('manager.orders.update', ['id' => $order->order_id]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            @if ($order->order_status == "created")
                            <input type="hidden" name="type" value="confirm" />
                            <input class="btn btn-primary w-100" name="submit" type="submit" value="{{ __('manager_orders.confirm') }}">
                            @elseif ($order->order_status == "processing")
                            <input type="hidden" name="type" value="complete" />
                            <input class="btn btn-primary w-100" name="submit" type="submit" value="{{ __('manager_orders.complete') }}">
                            @endif
                        </form>
                        <form class="form-group w-100">
                            <a class="btn btn-primary w-100" href="{{ route('manager.orders.edit', ['id' => $order->order_id ]) }}" target="_blank" role="button">{{ __('manager_orders.edit') }}</a>
                        </form>
                        <form class="form-group w-100" action="{{ route('manager.orders.update', ['id' => $order->order_id ]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="type" value="cancel" />
                            <input class="btn btn-primary w-100" name="submit" type="submit" value="{{ __('manager_orders.cancel') }}">
                        </form>
                    </div>
                </td>
                @endif
                @endisset
                </td>
            </tr>
            @endisset
            @endforeach
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
@if (!empty($orders))
<div class="d-flex justify-content-center">
    {!! $orders->links() !!}
</div>
@endif
@endsection
@section('footer')
@parent
<script>
    $('#input_search').ready(function() {
        $("#input_search").attr("placeholder", "{{ __('manager_orders.search')}}");
    });
</script>
@endsection