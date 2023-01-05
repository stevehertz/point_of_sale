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
    <!-- IonIcons -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Product Code</th>
                                <td>{{ $product->product_code }}</td>
                            </tr>
                            <tr>
                                <th>Barcode</th>
                                <td>{!! $product->barcodes !!}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $product->product }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $category->category }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ $product->product_type }}</td>
                            </tr>
                            <tr>
                                <th>Brand</th>
                                <td>{{ $brand->brand }}</td>
                            </tr>
                            <tr>
                                <th>Purchase Price</th>
                                <td>{{ $product->purchase_price }}</td>
                            </tr>
                            <tr>
                                <th>Selling Price</th>
                                <td>{{ $product->selling_price }}</td>
                            </tr>
                            <tr>
                                <th>Unit</th>
                                <td>{{ $unit->short_name }}</td>
                            </tr>
                            <tr>
                                <th>Purchase Unit</th>
                                <td>{{ $product->purchase_unit }}</td>
                            </tr>
                            <tr>
                                <th>Selling Unit</th>
                                <td>{{ $product->sale_unit }}</td>
                            </tr>
                            <tr>
                                <th>Available Stocks</th>
                                <td>{{ $product->stocks }}</td>
                            </tr>
                        </tbody>
                    </table>
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
