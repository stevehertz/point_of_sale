@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Purchase</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.purchases.index', $organization->id) }}">
                                Purchase Lists
                            </a>
                        </li>
                        <li class="breadcrumb-item active">View Purchase</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <img src="{{ asset('storage/organizations') }}/{{ $organization->logo }}"
                                        class="img-circle img-size-32 mr-2" alt="Logo"> {{ $organization->organization }}
                                    <small class="float-right">
                                        Date: {{ $purchase->purchase_date }}
                                    </small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>

                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                Supplier:
                                <address>
                                    <strong>{{ $supplier->full_names }}</strong><br>
                                    {{ $supplier->address }}<br>
                                    Phone: {{ $supplier->phone }}<br>
                                    Email: {{ $supplier->email }}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                Organization:
                                <address>
                                    <strong>{{ $organization->organization }}</strong><br>
                                    {{ $organization->address }}<br>
                                    Phone: {{ $organization->phone }}<br>
                                    Email: {{ $organization->email }}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Purchase #: {{ $purchase->id }}</b><br>
                                <br>
                                <b>Purchase Date:</b> {{ $purchase->purchase_date }}<br>
                                &nbsp;<br>
                                &nbsp;
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-border table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Purchase Price</th>
                                            <th>Quantity</th>
                                            <th>Item Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($purchase_products as $product)
                                            <tr>
                                                <td>{{ $product->product->product }}</td>
                                                <td> {{ number_format($product->product->purchase_price, 2, '.', ',') }}
                                                </td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>{{ number_format($product->total_amount, 2, '.', ',') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    No Products added yet
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-md-6 col-12">
                                {{-- <br> --}}
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6 col-12">
                                <p class="lead">
                                    Purchase Amount Due {{ $purchase->purchase_date }}
                                </p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Order Total:</th>
                                            <td>{{ number_format($purchase->order_amount, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td>
                                                {{ number_format($purchase->discount, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Subtotal:</th>
                                            <td>{{ number_format($purchase->subtotal, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Prev Balance:</th>
                                            <td>{{ number_format($supplier->balance, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>{{ number_format($purchase->total_amount, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Paid Amount:</th>
                                            <td>{{ number_format($purchase->paid_amount, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Balance:</th>
                                            <td>{{ number_format($purchase->balance, 2, '.', ',') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12 col-md-6">
                                <a href="#" data-id="{{ $purchase->id }}"
                                    data-path="{{ route('back.purchases.show') }}" data-token="{{ csrf_token() }}"
                                    class="btn btn-warning editPurchaseBtn" style="margin-right: 7px;">
                                    <i class="fa fa-edit"></i> Edit Purchase
                                </a>

                                <a href="#" data-id="{{ $purchase->id }}" data-path="{{ route('back.purchases.show') }}" data-token="{{ csrf_token() }}" target="_blank" class="btn btn-default printBtn">
                                    <i class="fa fa-print"></i> Print
                                </a>
                            </div>

                            <div class="col-12 col-md-6">
                                <a href='{{ route('back.purchases.send', $organization->id) }}'
                                    class="btn btn-success float-right" target="_blank">
                                    <i class="fa fa-send"></i> Send Purchase To Supplier
                                </a>


                                <button type="button" data-id="{{ $purchase->id }}" data-token="{{ csrf_token() }}" data-path="{{ route('back.purchases.show') }}" class="btn btn-primary float-right pdfBtn" style="margin-right: 7px;">
                                    <i class="fa fa-download"></i> Generate PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.invoice -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click', '.editPurchaseBtn', function(e){
                e.preventDefault();
                var purchase_id = $(this).data('id');
                var path = $(this).data('path');
                var token = $(this).data('token');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        purchase_id: purchase_id
                    },
                    dataType:"json",
                    success: function(data) {
                       if(data['status'] == false){
                           toastr.error(data['error']);
                       }else{
                           console.log(data);
                       }
                    }
                });
            });

            $(document).on('click', '.printBtn', function(e){
                e.preventDefault();
                var purchase_id = $(this).data('id');
                var path = $(this).data('path');
                var token = $(this).data('token');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        purchase_id: purchase_id
                    },
                    dataType:"json",
                    success: function(data) {
                       if(data['status'] == false){
                           toastr.error(data['error']);
                       }else{
                           var purchase_url = '{{ route('back.purchases.print', $organization->id) }}';
                            window.open(purchase_url, '_blank');
                       }
                    }
                });
            });

            $(document).on('click', '.pdfBtn', function(e){
                e.preventDefault();
                var purchase_id = $(this).data('id');
                var path = $(this).data('path');
                var token = $(this).data('token');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        purchase_id: purchase_id
                    },
                    dataType:"json",
                    success: function(data) {
                       if(data['status'] == false){
                           toastr.error(data['error']);
                       }else{
                           var pdf_url = '{{ route('back.purchases.pdf', $organization->id) }}';
                            window.open(pdf_url, '_blank');
                       }
                    }
                });
            });
        });
    </script>
@endsection
