<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} | {{ $page_title }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 4 -->
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">

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
                        <small class="float-right">
                            Date: {{ $purchase->purchase_date }}
                        </small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->

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
                    {{-- <p class="lead">Payment Methods:</p>
                <form id="paymentMethodForm">
                    <div class="form-group">
                        <select class="form-control select2" style="width: 100%;">
                            <option selected="selected" disabled="disabled">
                                Payment Method
                            </option>
                        </select>
                    </div>
                </form> --}}
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
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->

    <script type="text/javascript">
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
