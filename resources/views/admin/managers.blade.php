@extends('admin.layouts.master')
@section('title', __('admin_managers.title'))
@section('content')
<h4 class="category_header">
{{ __('admin_managers.header')}}
</h4>
<br />
<a class="btn btn-primary" href="{{ route('admin.manager.new') }}" role="button" type="button">
{{__('admin_managers.add_btn')}}
</a>
<br />
<br />
<div id="app">
        <example-component></example-component>
    </div>
<table class="table table-hover sortable-theme-ligh" data-sortable>
  <thead>
    <tr>
      <th scope="col" class="small-column text-center">{{ __('admin_managers.f_name')}}</th>
      <th scope="col" class="w_7 text-center">{{ __('admin_managers.l_name')}}</th>
      <th scope="col" class="w_7 text-center">{{ __('admin_managers.m_name')}}</th>
      <th scope="col" class="w_15 text-center">{{ __('admin_managers.phone')}}</th>
      <th scope="col" class="small-column text-center">{{ __('admin_managers.email')}}</th>
      <th scope="col" class="small-column text-center">{{ __('admin_managers.orders_taken')}}</th>
      <th scope="col" data-sortable="false" class="small-column text-center">{{ __('admin_managers.action')}}</th>
    </tr>
  </thead>
  <tbody id="content-data">
  @if (!empty($managers))
        @foreach ($managers as $index => $manager)
            <tr>
            <td scope="row" class="text-center">{{ $manager->manager_fname}}</td>
            <td class="text-center">{{ $manager->manager_lname }}</td>
            <td class="text-center">{{ $manager->manager_mname }}</td>
            <td class="text-center">{{ $manager->manager_phone}}</td>
            <td class="text-center">{{ $manager->email }}</td>
            <td class="text-center">{{ $manager->orders_count }}</td>
            <td>
            <form action="{{ route('admin.manager.delete', ['id' => $manager->manager_id]) }}" method="POST">
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2" role="group" aria-label="First group">
                    <a class="btn btn-primary" href="{{ route('admin.manager.orders', ['id' => $manager->manager_id]) }}" role="button" target="_blank" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    </a>
                    <a class="btn btn-primary" href="{{ route('admin.manager.edit', ['id' => $manager->manager_id]) }}" role="button" target="_blank" type="button">
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
            <h5>{{ __('manager_orders.not_found') }}</h5>
            </td>
        </tr>
        @endif
  </tbody>
</table>
</div>
<hr>
@if (!empty($managers))
<div class="d-flex justify-content-center">
{!! $managers->links() !!}
</div>
@endif
@endsection
@section('footer')
@parent
@endsection
