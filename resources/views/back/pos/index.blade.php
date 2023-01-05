<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>{{ config('app.name') }} | {{ $page_title }}</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome/css/font-awesome.min.css') }}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- daterange picker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="{{ route('back.dashboard.index', $organization->id) }}" class="navbar-brand">
                    <img src="{{ asset('storage/organizations') }}/{{ $organization->logo }}" alt="AdminLTE Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">{{ $organization->organization }}</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}"
                                class="nav-link">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">POS</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('back.dashboard.index', $organization->id) }}">
                                        Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Point of Sales
                                </li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="posNewCustomer">Customer</label>
                                                <select class="form-control select2 select2-purple"
                                                    data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                    <option selected="selected" disabled="disabled">Choose Customer
                                                    </option>
                                                    @forelse ($customers as $customer)
                                                        <option value="{{ $customer->id }}">
                                                            {{ $customer->full_names }}</option>
                                                    @empty
                                                        <option disabled="disabled">No Customer Data</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row posProducts">
                                        <div class="col-12 table-responsive p-0">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Price</th>
                                                        <th>Qty</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="4" class="text-center">
                                                            No data Available
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="bg-info color-palette posGrandTotal text-center">
                                                <strong>Grand Total: </strong><span>0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Tax</label>
                                                <div class="input-group">
                                                    <input type="number" value="0" class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-sm btn-block btn-primary">Enter</button>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Discount</label>
                                                <div class="input-group">
                                                    <input type="number" value="0" class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-sm btn-block btn-success">
                                                    Enter
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Shipping</label>
                                                <div class="input-group">
                                                    <input type="number" value="0" class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-sm btn-block btn-secondary">
                                                    Enter
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button class="btn btn-block btn-danger posReset">
                                                <i class="fa fa-clock-o"></i>
                                                Reset
                                            </button>
                                        </div>

                                        <div class="col-md-6">
                                            <button class="btn btn-block btn-primary posSave">
                                                <i class="fa fa-money"></i>
                                                Pay
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col-md-6 -->

                        <div class="col-md-7 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="posCategoryList">List of Category</label>
                                                <select id="posCategoryList" class="form-control select2 select2-danger"
                                                    data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                    <option selected="selected" disabled="disabled">Choose Categories
                                                    </option>
                                                    @forelse ($categories as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->category }}
                                                        </option>
                                                    @empty
                                                        <option disabled="disabled">
                                                            No Category List Found
                                                        </option>
                                                    @endforelse
                                                </select>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Brand List</label>
                                                <select class="form-control select2 select2-danger"
                                                    data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                    <option disabled="disabled" selected="selected">Choose Brand
                                                    </option>
                                                    @forelse ($brands as $brand)
                                                        <option value="{{ $brand->id }}">
                                                            {{ $brand->brand }}
                                                        </option>
                                                    @empty
                                                        <option disabled="disabled">
                                                            No Brand List
                                                        </option>
                                                    @endforelse
                                                </select>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-md">
                                                    <input type="text" class="form-control"
                                                        placeholder="Scan/Search Product by Code Name" />
                                                    <span class="input-group-append">
                                                        <button type="button" class="btn btn-info btn-flat">Go!</button>
                                                    </span>
                                                </div>
                                                <!-- /input-group -->
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12 table-responsive p-0">
                                            <table class="table table-hover text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Code</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($products as $product)
                                                        <tr>
                                                            <td>{{ $product->product }}</td>
                                                            <td>{{ $product->product_code }}</td>
                                                            <td>{{ $product->selling_price }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="3">
                                                                No Products Found
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                <b>Version</b> 1.0.0
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2022</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->


    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <!-- bs-custom-file-input -->
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- SweetAlert2 -->
    {{-- <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script> --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    <!-- AdminLTE -->
    <script src="{{ asset('js/adminlte.js') }}"></script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2();
        });
    </script>
</body>

</html>
