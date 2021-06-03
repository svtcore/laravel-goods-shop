@extends('admin.layouts.master')
@section('title',  __('admin_home.title'))
@section('content')
<div class="main_statistics_block">
    <h5 class="h_center">{{ __('admin_home.statistics') }}</h5>
    <hr />
    <h5 class="h_center">{{ __('admin_home.orders') }}</h5>
    <div class="flex_blocks" >
    <div class="statistics_block">
        <table class="table table-hover">
        <thead>
            <th class="table_cell"></th>
            <th class="table_cell">{{ __('admin_home.today') }}</th>
            <th class="table_cell">{{ __('admin_home.7') }}</th>
            <th class="table_cell">{{ __('admin_home.14') }}</th>
            <th class="table_cell">{{ __('admin_home.30') }}</th>
            <th class="table_cell">{{ __('admin_home.90') }}</th>
            <th class="table_cell">{{ __('admin_home.180') }}</th>
        </thead>
        <tbody>
            <tr>
                <td class="table_cell">{{ __('admin_home.created') }}</td>
                <td class="table_cell">{{ $orders['0_days']['created'] }}</td>
                <td class="table_cell">{{ $orders['7_days']['created'] }}</td>
                <td class="table_cell">{{ $orders['14_days']['created'] }}</td>
                <td class="table_cell">{{ $orders['30_days']['created'] }}</td>
                <td class="table_cell">{{ $orders['90_days']['created'] }}</td>
                <td class="table_cell">{{ $orders['180_days']['created'] }}</td>
            </tr>
            <tr>
                <td class="table_cell">{{ __('admin_home.processing') }}</td>
                <td class="table_cell">{{ $orders['0_days']['processing'] }}</td>
                <td class="table_cell">{{ $orders['7_days']['processing'] }}</td>
                <td class="table_cell">{{ $orders['14_days']['processing'] }}</td>
                <td class="table_cell">{{ $orders['30_days']['processing'] }}</td>
                <td class="table_cell">{{ $orders['90_days']['processing'] }}</td>
                <td class="table_cell">{{ $orders['180_days']['processing'] }}</td>
            </tr>
            <tr>
                <td class="table_cell">{{ __('admin_home.canceled') }}</td>
                <td class="table_cell">{{ $orders['0_days']['canceled'] }}</td>
                <td class="table_cell">{{ $orders['7_days']['canceled'] }}</td>
                <td class="table_cell">{{ $orders['14_days']['canceled'] }}</td>
                <td class="table_cell">{{ $orders['30_days']['canceled'] }}</td>
                <td class="table_cell">{{ $orders['90_days']['canceled'] }}</td>
                <td class="table_cell">{{ $orders['180_days']['canceled'] }}</td>
            </tr>
            <tr>
                <td class="table_cell">{{ __('admin_home.completed') }}</td>
                <td class="table_cell">{{ $orders['0_days']['completed'] }}</td>
                <td class="table_cell">{{ $orders['7_days']['completed'] }}</td>
                <td class="table_cell">{{ $orders['14_days']['completed'] }}</td>
                <td class="table_cell">{{ $orders['30_days']['completed'] }}</td>
                <td class="table_cell">{{ $orders['90_days']['completed'] }}</td>
                <td class="table_cell">{{ $orders['180_days']['completed'] }}</td>
            </tr>
            <tr>
                <td class="table-info table_cell">{{ __('admin_home.total') }}</td>
                <td class="table-info table_cell">{{ $orders['0_days']['total'] }}</td>
                <td class="table-info table_cell">{{ $orders['7_days']['total'] }}</td>
                <td class="table-info table_cell">{{ $orders['14_days']['total'] }}</td>
                <td class="table-info table_cell">{{ $orders['30_days']['total'] }}</td>
                <td class="table-info table_cell">{{ $orders['90_days']['total'] }}</td>
                <td class="table-info table_cell">{{ $orders['180_days']['total'] }}</td>
            </tr>
        </tbody>
        </table>
    </div>
    <div class="chart_block">
        <div id="piechart" class="w-100 h-100"></div>
    </div>
    </div>
    <hr />
    <h5 class="h_center">{{ __('admin_home.Incomes & Popular products') }} </h5>
    <div class="flex_blocks" >
    <div class="statistics_block">
        <table class="table table-hover">
        <thead>
            <th class="table_cell"></th>
            <th class="table_cell">{{ __('admin_home.today') }}</th>
            <th class="table_cell">{{ __('admin_home.7') }}</th>
            <th class="table_cell">{{ __('admin_home.14') }}</th>
            <th class="table_cell">{{ __('admin_home.30') }}</th>
            <th class="table_cell">{{ __('admin_home.90') }}</th>
            <th class="table_cell">{{ __('admin_home.180') }}</th>
        </thead>
        <tbody>
            <tr>
                <td class="table_cell">{{ __('admin_home.earned') }}</td>
                <td class="table_cell">{{ $incomes['0_days']['total'] }}</td>
                <td class="table_cell">{{ $incomes['7_days']['total'] }}</td>
                <td class="table_cell">{{ $incomes['14_days']['total'] }}</td>
                <td class="table_cell">{{ $incomes['30_days']['total'] }}</td>
                <td class="table_cell">{{ $incomes['90_days']['total'] }}</td>
                <td class="table_cell">{{ $incomes['180_days']['total'] }}</td>
            </tr>
        </tbody>
        </table>
    </div>
    <div class="popular_prod">
    <div class="list-group">
                        @foreach($prodcat as $index => $oproduct)
                            @if ($oproduct->products != null)
                            <a href="{{ route('user.product.show', ['id' => $oproduct->products->product_id]) }}" target="_blank" class="list-group-item w-100 list-group-item-action">
                                <b>№ {{ $index + 1 }} </b>
                                @if (app()->getLocale() == "en") 
                                    {{ $oproduct->products->names->product_name_lang_en }}
                                @elseif (app()->getLocale() == "de")
                                    {{ $oproduct->products->names->product_name_lang_de }}
                                @elseif (app()->getLocale() == "uk")
                                    {{ $oproduct->products->names->product_name_lang_uk }}
                                @elseif (app()->getLocale() == "ru")
                                    {{ $oproduct->products->names->product_name_lang_ru }}
                                @endif
                                </a>
                            @endif
                        @endforeach
                </div>
    </div>
    </div>
    <hr />
</div>
@endsection
@section('footer')
@parent
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          @foreach($prodcat as $index => $val)
                @if ($val->products != null)
                    @if (app()->getLocale() == "en") 
                        ['{{ $val->products->categories->catg_name_en }}', {{  $prodcat[$index]['count(order_p_count)'] }}],
                    @elseif (app()->getLocale() == "de")
                         ['{{ $val->products->categories->catg_name_de }}', {{  $prodcat[$index]['count(order_p_count)'] }}],
                    @elseif (app()->getLocale() == "uk")
                        ['{{ $val->products->categories->catg_name_uk }}', {{  $prodcat[$index]['count(order_p_count)'] }}],
                    @elseif (app()->getLocale() == "ru")
                        ['{{ $val->products->categories->catg_name_ru }}', {{  $prodcat[$index]['count(order_p_count)'] }}],
                    @endif
                @endif
          @endforeach
        ]);

        var options = {
          title: '{{ __('admin_home.Popular Categories') }}'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
@endsection
