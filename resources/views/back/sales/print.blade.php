<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} || {{ $page_title }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 4 -->

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome/css/font-awesome.min.css') }}">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h2 class="page-header">
                        <img src="{{ asset('storage/organizations') }}/{{ $organization->logo }}"
                            class="img-circle img-size-32 mr-2" alt="Logo"> {{ $organization->organization }}
                        <small class="float-right">Date: {{ $sale->sales_date }}</small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->

            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    Customer:
                    <address>
                        <strong>{{ $customer->full_names }}</strong><br>
                        @if ($customer->full_names != 'Walk-in')
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
                <div class="col-6">
                    @if ($sale->payment_method_id > 0)
                        <p class="lead">Payment Methods:</p>
                        <p class="text-muted well well-sm shadow-none">
                            {{ $payment_method->method }}
                        </p>
                    @endif
                </div>
                <!-- /.col -->
                <div class="col-6">
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
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->

    <script type="text/javascript">
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
