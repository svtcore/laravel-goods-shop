@extends('admin.layouts.master')
@section('title', __('admin_manager_orders.title'))
@section('content')
<h4 class="category_header">
{{ __('admin_manager_orders.header')}}
</h4>
<div class="container">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div id="app">
    <orders-search-component></orders-search-component>
</div>
<br />
<table class="table table-hover sortable-theme-ligh" data-sortable>
  <thead>
    <tr>
      <th scope="col" class="small-column">{{ __('admin_manager_orders.order_num_tb')}}</th>
      <th scope="col" class="small-column">{{ __('admin_manager_orders.date_num_tb')}}</th>
      <th scope="col" class="w-25">{{ __('admin_manager_orders.address_tb')}}</th>
      <th scope="col" class="small-column">{{ __('admin_manager_orders.price_tb')}}</th>
      <th scope="col" class="small-column">{{ __('admin_manager_orders.payment_tb')}}</th>
      <th scope="col" class="small-column">{{ __('admin_manager_orders.status_tb')}}</th>
      <th scope="col" data-sortable="false" class="small-column">{{ __('admin_manager_orders.action_tb')}}</th>
    </tr>
  </thead>
  <tbody id="content-data">
        @foreach ($managers as $manager)
            @if ($manager->orders->count() > 0) 
                @foreach ($manager->orders as $index => $order)
                @if ($order->deleted_at != null)
                    <tr class="c_grey">
                @else
                    <tr>
                @endif
                    <th scope="row" class="text-center">{{ $order->order_id }}</th>
                    <td class="text-center">{{ $order->created_at }}</td>
                    <td class="text-center">{{ $order->user_addresses->user_str_name }} {{ __('user_master.str_label') }}, {{ __('user_master.house_label') }} {{ $order->user_addresses->user_house_num }}, {{ __('user_master.ent_label') }}{{ $order->user_addresses->user_ent_num }}, {{ __('user_master.apart_label') }} {{ $order->user_addresses->user_apart_num }}</td>
                    <td class="text-center">{{ $order->order_full_price }}</td>
                    @if (isset($order->payment_types))
                        @if(isset($order->payment_types->deleted_at) && ($order->payment_types->deleted_at != null))
                            <td class="c_grey text-center">
                                {{ $order->payment_types->pay_t_name }}
                            </td>
                        @else
                            <td class="text-center">
                                {{ $order->payment_types->pay_t_name }}
                            </td>
                        @endif
                    @endif
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
                    <td>
                        <form action="{{ route('admin.order.delete', ['id' => $order->order_id]) }}" method="POST">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                <a class="btn btn-primary" href="{{ route('admin.order.show', ['id' => $order->order_id]) }}" role="button" target="_blank" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                    <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                </svg>
                                </a>
                                <a class="btn btn-primary" href="{{ route('admin.order.edit', ['id' => $order->order_id]) }}" role="button" target="_blank" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </a>
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        </form>
                    </td>
                </tr>
            @endforeach
            @else
            <tr>
                <td colspan="7" class="text-center">
                <h5>{{ __('admin_manager_orders.not_found')}}</h5>
                </td>
            </tr>
            @endif
        @endforeach
  </tbody>
</table>
</div>
<hr>
<div class="d-flex justify-content-center">
{!! $managers->links() !!}
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
