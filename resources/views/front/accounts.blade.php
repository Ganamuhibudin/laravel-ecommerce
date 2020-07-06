@extends('layouts.front.app')

@section('content')
    <!-- Main content -->
    <section class="container content">
        <div class="row">
            <div class="box-body">
                @include('layouts.errors-and-messages')
            </div>
            <div class="col-md-12">
                <h2> <i class="fa fa-home"></i> My Account</h2>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" @if(request()->input('tab') == 'profile') class="active" @endif><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
                        <li role="presentation" @if(request()->input('tab') == 'orders') class="active" @endif><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">Orders</a></li>
                        <li role="presentation" @if(request()->input('tab') == 'wishlist') class="active" @endif><a href="#wishlist" aria-controls="wishlist" role="tab" data-toggle="tab">Wishlist</a></li>
                        <li role="presentation" @if(request()->input('tab') == 'address') class="active" @endif><a href="#address" aria-controls="address" role="tab" data-toggle="tab">Addresses</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content customer-order-list">
                        <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'profile')active @endif" id="profile">
                            {{$customer->name}} <br /><small>{{$customer->email}}</small>
                        </div>
                        <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'orders')active @endif" id="orders">
                            @if(!$orders->isEmpty())
                                <table class="table">
                                <tbody>
                                <tr>
                                    <td>Date</td>
                                    <td>Total</td>
                                    <td>Status</td>
                                </tr>
                                </tbody>
                                <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <a data-toggle="modal" data-target="#order_modal_{{$order['id']}}" title="Show order" href="javascript: void(0)">{{ date('M d, Y h:i a', strtotime($order['created_at'])) }}</a>
                                            <!-- Button trigger modal -->
                                            <!-- Modal -->
                                            <div class="modal fade" id="order_modal_{{$order['id']}}" tabindex="-1" role="dialog" aria-labelledby="MyOrders">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Reference #{{$order['reference']}}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table">
                                                                <thead>
                                                                    <th>Address</th>
                                                                    <th>Payment Method</th>
                                                                    <th>Total</th>
                                                                    <th>Status</th>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <address>
                                                                                <strong>{{$order['address']->alias}}</strong><br />
                                                                                {{$order['address']->address_1}} {{$order['address']->address_2}}<br>
                                                                            </address>
                                                                        </td>
                                                                        <td>{{$order['payment']}}</td>
                                                                        <td>{{ config('cart.currency_symbol') }} {{$order['total']}}</td>
                                                                        <td><p class="text-center" style="color: #ffffff; background-color: {{ $order['status']->color }}">{{ $order['status']->name }}</p></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            @if($order['bank_receipt'] != "")
                                                                <table class="table">
                                                                    <tr>
                                                                        <td>Bank Receipt</td>
                                                                        <td>
                                                                        <img src="{{ asset("storage") . "/" . $order['bank_receipt'] }}" class="img-thumbnail" width="300" />
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            @else
                                                                <form method="post" id="upload_form" enctype="multipart/form-data">
                                                                    <div class="alert" id="message" style="display: none"></div>
                                                                    {{ csrf_field() }}
                                                                    <div class="form-group">
                                                                        <table class="table">
                                                                            <tr>
                                                                                <td width="30%" align="left"><label>Upload Bank Receipt</label></td>
                                                                                <td width="30">
                                                                                    <input type="hidden" name="id" value="{{ $order['id'] }}"/>
                                                                                    <input type="file" name="select_file" id="select_file" />
                                                                                </td>
                                                                                <td width="50%" align="left"><input type="submit" name="upload" id="upload" class="btn btn-primary" value="Upload"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </form>
                                                                <br />
                                                                <span id="uploaded_image"></span>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            @if($order['status']['name'] == 'on-delivery')
                                                                <button type="button" class="btn btn-success" data-order-id="{{ $order['id'] }}" id="btn-delivery-confirmation">Delivery Confirmation</button>
                                                            @endif
                                                            @if($order['status']['name'] == 'delivered')
                                                                <button type="button" class="btn btn-danger" data-order-id="{{ $order['id'] }}" id="btn-retur-order">Retur</button>
                                                            @endif
                                                            @if($order['status']['name'] == 'waiting for payment')
                                                                <button type="button" class="btn btn-danger" data-order-id="{{ $order['id'] }}" id="btn-cancel-order">Cancel Order</button>
                                                            @endif
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="label @if($order['total'] != $order['total_paid']) label-danger @else label-success @endif">{{ config('cart.currency') }} {{ $order['total'] }}</span></td>
                                        <td><p class="text-center" style="color: #ffffff; background-color: {{ $order['status']->color }}">{{ $order['status']->name }}</p></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                                {{ $orders->links() }}
                            @else
                                <p class="alert alert-warning">No orders yet. <a href="{{ route('home') }}">Shop now!</a></p>
                            @endif
                        </div>
                        <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'wishlist')active @endif" id="wishlist">
                            @if(!$wishlist->isEmpty())
                                <table class="table">
                                <tbody>
                                <tr>
                                    <td>Product</td>
                                    <td>SKU</td>
                                    <td>Price</td>
                                    <td>Action</td>
                                </tr>
                                </tbody>
                                <tbody>
                                @foreach ($wishlist as $wishlist)
                                    <tr>
                                        <td><a href="{{ route('front.get.product', str_slug($wishlist->slug)) }}">{{ $wishlist->name }}</a></td>
                                        <td>{{ $wishlist->sku }}</td>
                                        <td>{{ $wishlist->price }}</td>
                                        <td>
                                        <button onclick="removeWishlist({{ $wishlist->id }})" class="btn btn-danger"><i class="fa fa-trash"></i> Remove</button>
                                        </td>
                                    <tr>
                                @endforeach
                                </tbody>
                            </table>
                            @else
                                <p class="alert alert-warning">No wishlist yet. <a href="{{ route('home') }}">Shop now!</a></p>
                            @endif
                        </div>
                        <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'address')active @endif" id="address">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('customer.address.create', auth()->user()->id) }}" class="btn btn-primary">Create your address</a>
                                </div>
                            </div>
                            @if(!$addresses->isEmpty())
                                <table class="table">
                                <thead>
                                    <th>Alias</th>
                                    <th>Address 1</th>
                                    <th>Address 2</th>
                                    <th>City</th>
                                    @if(isset($address->province))
                                    <th>Province</th>
                                    @endif
                                    <th>State</th>
                                    <th>Country</th>
                                    <th>Zip</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                    @foreach($addresses as $address)
                                        <tr>
                                            <td>{{$address->alias}}</td>
                                            <td>{{$address->address_1}}</td>
                                            <td>{{$address->address_1}}</td>
                                            <td>{{$address->city}}</td>
                                            @if(isset($address->province))
                                            <td>{{$address->province->name}}</td>
                                            @endif
                                            <td>{{$address->state_code}}</td>
                                            <td>{{$address->country->name}}</td>
                                            <td>{{$address->zip}}</td>
                                            <td>{{$address->phone}}</td>
                                            <td>
                                                <form method="post" action="{{ route('customer.address.destroy', [auth()->user()->id, $address->id]) }}" class="form-horizontal">
                                                    <div class="btn-group">
                                                        <input type="hidden" name="_method" value="delete">
                                                        {{ csrf_field() }}
                                                        <a href="{{ route('customer.address.edit', [auth()->user()->id, $address->id]) }}" class="btn btn-primary"> <i class="fa fa-pencil"></i> Edit</a>
                                                        <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i> Delete</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <br /> <p class="alert alert-warning">No address created yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@section('js')
    <script type="text/javascript">
        $('#upload_form').on('submit', function(event){
            event.preventDefault();
            let urlUpload = "{{ route('orders.upload') }}";
            $.ajax({
                url:urlUpload,
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    $('#message').css('display', 'block');
                    $('#message').html(data.message);
                    $('#message').addClass(data.class_name);
                    $('#uploaded_image').html(data.uploaded_image);
                }
            })
        });

        $('#btn-delivery-confirmation').on('click', function(event){
            event.preventDefault();
            let orderID = $(this).data('order-id');
            let successUrl = "{{ route('accounts', ['tab' => 'orders']) }}"
            let url = "{{ route('orders.confirmation', ':id') }}";
            url = url.replace(':id', orderID);

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    window.location.href = successUrl;
                }
            });
        });

        $('#btn-retur-order').on('click', function(event){
            event.preventDefault();
            let orderID = $(this).data('order-id');
            let successUrl = "{{ route('accounts', ['tab' => 'orders']) }}"
            let url = "{{ route('orders.retur', ':id') }}";
            url = url.replace(':id', orderID);

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    window.location.href = successUrl;
                }
            });
        });

        $('#btn-cancel-order').on('click', function(event){
            event.preventDefault();
            let orderID = $(this).data('order-id');
            let successUrl = "{{ route('accounts', ['tab' => 'orders']) }}"
            let url = "{{ route('orders.cancel', ':id') }}";
            url = url.replace(':id', orderID);

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    window.location.href = successUrl;
                }
            });
        });
        
        function removeWishlist(id) {
            let successUrl = "{{ route('accounts', ['tab' => 'wishlist']) }}"
            let url = "{{ route('wishlist.remove', ':id') }}";
            url = url.replace(':id', id);

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    window.location.href = successUrl;
                }
            });
        }
    </script>
@endsection
