@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-body">
                <h2>Orders Report</h2>
                <div class="col-md-12">
                <form action="{{ route('admin.reports.orders.generate') }}" method="POST" id="report-search">
                    {{ csrf_field() }}
                    <div class="input-group col-md-3">
                        <input type="text" id="reservation" name="dates" class="form-control daterange" placeholder="Choose Periode" value="{{request()->input('dates')}}">
                        <span class="input-group-btn">
                            <button type="submit" id="search-order-report" class="btn btn-flat"><i class="fa fa-search"></i> Search </button>
                        </span>
                    </div>
                </form>
                </div>
                <div class="col-md-12" >
                @if( ! empty($orders))                
                    <h4>Total Orders : {{ count($orders) }}</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="col-md-3">Date</td>
                                <td class="col-md-3">Customer</td>
                                <td class="col-md-3">Reference</td>
                                <td class="col-md-2">Total</td>
                                <td class="col-md-2">Status</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td><a title="Show order">{{ date('M d, Y h:i a', strtotime($order->created_at)) }}</a></td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->reference }}</td>
                                <td>
                                    <span class="label @if($order->total != $order->total_paid) label-danger @else label-success @endif">{{ config('cart.currency') }} {{ $order->total }}</span>
                                </td>
                                <td><p class="text-center" style="color: #ffffff; background-color: {{ $order->status_color }}">{{ $order->status_name }}</p></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
@section('js')
    <script type="text/javascript">
        $(function() {
            $('input[name="dates"]').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            });
        });
    </script>
@endsection
