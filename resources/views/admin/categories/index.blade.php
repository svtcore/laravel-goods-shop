@extends('admin.layouts.master')
@section('title', __('admin_categories.title'))
@section('content')
<h4 class="category_header">
    {{ __('admin_categories.header')}}
</h4>
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <a class="btn btn-primary categories_btn" href="{{ route('admin.categories.create') }}" role="button" type="button">
        {{ __('admin_categories.add_btn')}}
    </a>
    <br /><br />
    <table class="table table-hover sortable-theme-ligh table_data" data-sortable>
        <thead>
            <tr>
                <th scope="col" class="w_15 text-center">{{ __('admin_categories.name')}}</th>
                <th scope="col" data-sortable="false" class="small-column text-center">{{ __('admin_categories.action')}}</th>
            </tr>
        </thead>
        <tbody id="content-data">
            @if (count($categories) > 0)
            @foreach ($categories as $index => $category)
            <tr>
                <td scope="row" class="text-center">
                    @if (app()->getLocale() == "en")
                    {{ $category->catg_name_en }}
                    @elseif (app()->getLocale() == "de")
                    {{ $category->catg_name_de }}
                    @elseif (app()->getLocale() == "uk")
                    {{ $category->catg_name_uk }}
                    @elseif (app()->getLocale() == "ru")
                    {{ $category->catg_name_ru }}
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.categories.delete', ['id' => $category->catg_id]) }}" method="POST">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                <a class="btn btn-primary" href="{{ route('admin.categories.edit', ['id' => $category->catg_id]) }}" role="button" type="button">
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
                <td colspan="2" class="text-center">
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