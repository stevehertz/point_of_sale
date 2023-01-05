@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Stocks Summary</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Stocks</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="stocksData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Opening stock</th>
                                        <th>Purchased stock</th>
                                        <th>Total stock</th>
                                        <th>Sold stock</th>
                                        <th>Remaining stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(function() {
            find_stocks();
            function find_stocks() {
                var path = '{{ route('back.stocks.index', $organization->id) }}';
                $("#stocksData").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: path,
                    "autoWidth": false,
                    "responsive": true,
                    columns: [
                        {
                            data: 'product_name',
                            name: 'product_name'
                        },
                        {
                            data: 'opening_stock',
                            name: 'opening_stock'
                        },
                        {
                            data: 'purchased_stock',
                            name: 'purchased_stock'
                        },
                        {
                            data: 'total_stock',
                            name: 'total_stock'
                        },
                        {
                            data: 'sold_stock',
                            name: 'sold_stock'
                        },
                        {
                            data: 'closing_stock',
                            name: 'closing_stock'
                        }
                    ],
                    "order": [[ 0, "desc" ]],
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "autoWidth": false,
                    "responsive": true,
                });
            }
        });
    </script>
@endsection
