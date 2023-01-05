@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Purchases List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Purchases</li>
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
                                <a href="{{ route('back.purchases.create', $organization->id) }}" class="btn btn-info">
                                    <i class="fa fa-plus"></i> NEW PURCHASE
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-10">
                            <table id="purchaseData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th>Order Amount</th>
                                        <th>Discount</th>
                                        <th>Prev Balance</th>
                                        <th>Bill Paid</th>
                                        <th>Balance</th>
                                        <th>view</th>
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

            find_purchases();

            function find_purchases() {
                var path = '{{ route('back.purchases.index', $organization->id) }}'
                $("#purchaseData").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: path,
                    "autoWidth": false,
                    "responsive": true,
                    columns: [{
                            data: 'purchase_date',
                            name: 'purchase_date'
                        },
                        {
                            data: 'supplier_name',
                            name: 'supplier_name'
                        },
                        {
                            data: 'order_amount',
                            name: 'order_amount'
                        },
                        {
                            data: 'discount',
                            name: 'discount'
                        },
                        {
                            data: 'prev_balance',
                            name: 'prev_balance'
                        },
                        {
                            data: 'paid_amount',
                            name: 'paid_amount'
                        },
                        {
                            data: 'balance',
                            name: 'balance'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            }

            $(document).on('click', '.viewBtn', function(e) {
                e.preventDefault();
                var purchase_id = $(this).attr('id');
                var path = '{{ route('back.purchases.show') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: {
                        purchase_id: purchase_id,
                        _token: token,
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            window.location.href =
                                '{{ route('back.purchases.view', $organization->id) }}';
                        }
                    },

                });

            });


        });
    </script>
@endsection
