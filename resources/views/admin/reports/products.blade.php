@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-body">
                <h2>Best Seller Products Report</h2>
                <div class="col-md-12">
                <button class="btn btn-flat"><i class="fa fa-print" aria-hidden="true"> Download</i>
                </div>
                <div class="col-md-12" >
                @if(! empty($products))
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="col-md-3">ID</td>
                                <td class="col-md-3">SKU</td>
                                <td class="col-md-3">Name</td>
                                <td class="col-md-2">Description</td>
                                <td class="col-md-2">Total Sold</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->qty }}</td>
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
        
    </script>
@endsection
