@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Sale #{{ $sale->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.sales.index', $organization->id) }}">
                                Sales List
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Sale #{{ $sale->id }}</li>
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
                                        class="img-circle img-size-32 mr-2" alt="Logo">
                                    {{ $organization->organization }}
                                    <small class="float-right">Date: {{ $sale->sales_date }}</small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->

                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                Customer:
                                <address>
                                    @if ($customer == 'walk-in')
                                        <strong>{{ $sale->customer_name }}</strong><br>
                                    @else
                                        <strong>{{ $sale->customer_name }}</strong><br>
                                        {{ $customer->address }}<br>
                                        Phone: {{ $customer->phone }}<br>
                                        Email: {{ $customer->email }}
                                    @endif
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
                                <b>Sale #: {{ $sale->id }}</b><br>
                                <br>
                                <b>Sale Date:</b> {{ $sale->sales_date }}<br>
                                <b>Payment Status</b>
                                @if ($sale->payment_status == 1)
                                    <span class="badge badge-success">Paid</span>
                                @else
                                    <span class="badge badge-danger">Unpaid</span>
                                @endif
                                <br>
                                <b>Status:</b>
                                @if ($sale->sale_status == 1)
                                    <span class="badge badge-success">Complete</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
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
                                            <th>Sale Price</th>
                                            <th>Quantity</th>
                                            <th>Item Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($sale_products as $product)
                                            <tr>
                                                <td>{{ $product->product->product }}</td>
                                                <td>{{ number_format($product->sale_price, 2, '.', ',') }}</td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>{{ number_format($product->total_price, 2, '.', ',') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No Products added yet</td>
                                            </tr>
                                        @endforelse
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-md-6 col-12">
                                &nbsp;
                            </div>
                            <!-- /.col -->

                            <div class="col-md-6 col-12">
                                <p class="lead">
                                    Amount Due {{ $sale->sales_date }}
                                </p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Order Total:</th>
                                            <td>{{ number_format($sale->order_total, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">
                                                Sale Tax
                                            </th>
                                            <td>
                                                {{ number_format($sale->sale_tax, 2, '.', ',') }}({{ $sale->tax }}%)
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Discount:</th>
                                            <td>
                                                {{ number_format($sale->discount, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Shipping:</th>
                                            <td>
                                                {{ number_format($sale->shipping, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Subtotal</th>
                                            <td>{{ number_format($sale->subtotal, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Prev Balance</th>
                                            <td>{{ number_format($sale->prev_balance, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>{{ number_format($sale->total, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Paid Amount:</th>
                                            <td>{{ number_format($sale->paid, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Balance:</th>
                                            <td>{{ number_format($sale->balance, 2, '.', ',') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="#" data-id="{{ $sale->id }}" class="btn btn-success editSaleBtn">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <button type="button" data-id='{{ $sale->id }}'
                                    data-path="{{ route('back.sales.show') }}" data-token="{{ csrf_token() }}"
                                    class="btn btn-primary generatePDFBtn" style="margin-right: 5px;">
                                    <i class="fa fa-download"></i> Generate PDF
                                </button>

                                <button type="button" data-id='{{ $sale->id }}'
                                    data-path="{{ route('back.sales.show') }}" data-token="{{ csrf_token() }}"
                                    class="btn btn-default sendEmailBtn" style="margin-right: 5px;">
                                    <i class="fa fa-envelope"></i> Send Email
                                </button>

                                <a href="#" data-id="{{ $sale->id }}"
                                    data-path="{{ route('back.sales.show') }}" data-token="{{ csrf_token() }}"
                                    class="btn btn-secondary printSaleBtn" style="margin-right: 5px;">
                                    <i class="fa fa-print"></i> Print
                                </a>
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

            $(document).on('click', '.editSaleBtn', function(e) {
                e.preventDefault();
                var sale_id = $(this).data('id');
                var token = '{{ csrf_token() }}';
                var path = '{{ route('back.sales.show') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        sales_id: sale_id
                    },
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            var salePath =
                                '{{ route('back.sales.edit', $organization->id) }}';
                            setTimeout(function() {
                                window.location.href = salePath;
                            }, 1000);
                        }
                    }
                });
            });

            // print sale
            $(document).on('click', '.printSaleBtn', function(e) {
                e.preventDefault();
                var path = $(this).data('path');
                var token = $(this).data('token');
                var sale_id = $(this).data('id');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        sales_id: sale_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            var printURL =
                                '{{ route('back.sales.print', $organization->id) }}';
                            window.open(printURL, '_blank');
                        }
                    }
                });
            });

            // Generate pdf
            $(document).on('click', '.generatePDFBtn', function(e) {
                e.preventDefault();
                var path = $(this).data('path');
                var token = $(this).data('token');
                var sale_id = $(this).data('id');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        sales_id: sale_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            var printURL =
                            '{{ route('back.sales.pdf', $organization->id) }}';
                            window.open(printURL, '_blank');
                        }
                    }
                });
            });

            //send to mail
            $(document).on('click', '.sendEmailBtn', function(e) {
                e.preventDefault();
                var path = $(this).data('path');
                var token = $(this).data('token');
                var sale_id = $(this).data('id');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        sales_id: sale_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            send_mail(data['data']['id']);
                        }
                    }
                });
            });

            function send_mail(sale_id) {
                var organization_id = '{{ $organization->id }}';
                var path = '{{ route('back.sales.send') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        sale_id: sale_id,
                        organization_id: organization_id,
                        _token: token,
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == true) {
                            toastr.success(data['message']);
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            }
        });
    </script>
@endsection
