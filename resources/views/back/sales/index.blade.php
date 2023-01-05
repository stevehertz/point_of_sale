@extends('back.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sales List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Sales</li>
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
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('back.sales.create', $organization->id) }}" class="btn btn-info">
                                    <i class="fa fa-plus"></i> NEW SALE
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-10">
                            <table id="salesData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Order Amount</th>
                                        <th>Discount</th>
                                        <th>Subtotal</th>
                                        <th>Prev Balance</th>
                                        <th>Total</th>
                                        <th>Paid Amount</th>
                                        <th>Balance</th>
                                        <th>View</th>
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
            find_sales();
            function find_sales() {
                var path = '{{ route('back.sales.index', $organization->id) }}';
                $('#salesData').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: path,
                    "responsive": true,
                    "autoWidth": false,
                    columns: [
                        { data: 'sales_date', name: 'sales_date' },
                        { data: 'customer_name', name: 'customer_name' },
                        { data: 'order_total', name: 'order_total' },
                        { data: 'discount', name: 'discount' },
                        { data: 'subtotal', name: 'subtotal' },
                        { data: 'prev_balance', name: 'prev_balance' },
                        { data: 'total', name: 'total' },
                        { data: 'paid', name: 'paid' },
                        { data: 'balance', name: 'balance' },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ]
                });
            }

            $(document).on('click', '.viewBtn', function(e){
                e.preventDefault();
                var sales_id = $(this).attr('id');
                var path = '{{ route('back.sales.show', $organization->id) }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        sales_id: sales_id,
                        _token: token
                    },
                    dataType:"json",
                    success: function(data) {
                        if(data['status'] == false){
                            toastr.error(data['error']);
                        }else{
                            var url = '{{ route('back.sales.view', $organization->id) }}';
                            window.location.href = url;
                        }
                    }
                });
            });
        });
    </script>
@endsection
