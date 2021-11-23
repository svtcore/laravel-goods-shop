@extends('admin.layouts.master')
@section('title', __('admin_payments.title'))
@section('content')
<h4 class="category_header">
    {{ __('admin_payments.header')}}
</h4>
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <br />
    <a class="btn btn-primary w_30 add_btn" href="{{ route('admin.payments.create') }}" role="button" type="button">
        {{ __('admin_payments.add_btn')}}
    </a>
    <br /><br />
    <table class="table table-hover sortable-theme-ligh data_small_table" data-sortable>
        <thead>
            <tr>
                <th scope="col" class="w_15 text-center">{{ __('admin_payments.name')}}</th>
                <th scope="col" class="w_15 text-center">{{ __('admin_payments.available')}}</th>
                <th scope="col" data-sortable="false" class="small-column">{{ __('admin_payments.action')}}</th>
            </tr>
        </thead>
        <tbody id="content-data">
            @if (count($payments) > 0)
            @foreach ($payments as $index => $payment)
            <tr>
                <td scope="row" class="text-center">{{ $payment->pay_t_name}}</td>
                <td scope="row" class="text-center">
                    @if ($payment->pay_t_exst) {{ __('admin_payments.yes')}}
                    @elseif($payment->pay_t_exst == 0) {{ __('admin_payments.no')}}
                    @else None
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.payments.delete', ['id' => $payment->pay_t_id]) }}" method="POST">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                <a class="btn btn-primary" href="{{ route('admin.payments.edit', ['id' => $payment->pay_t_id]) }}" role="button" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                    </svg>
                                </a>
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
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
                <td colspan="3" class="text-center">
                    <h5>{{ __('manager_orders.not_found') }}</h5>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
<hr>
@endsection
@section('footer')
@parent
@endsection