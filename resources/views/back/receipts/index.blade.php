@extends('back.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Receipts List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Receipts</li>
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
                        <div class="card-body table-responsive p-10">
                            <table id="receiptsData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
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
        $(document).ready(function() {

            find_receipts();
            function find_receipts() {
                var path = '{{ route('back.receipts.index', $organization->id) }}';
                $('#receiptsData').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: path,
                    columns: [
                        { data: 'payment_date', name: 'payment_date' },
                        { data: 'type', name: 'type' },
                        { data: 'customer_name', name: 'customer_name' },
                        { data: 'total_amount', name: 'total_amount' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    "order": [[ 0, "desc" ]],
                    "responsive": true,
                    "bDestroy": true,
                    "autowidth": false,
                });
            }

            $(document).on('click', '.viewReceiptBtn', function(e){
                e.preventDefault();
                var receipt_id = $(this).attr('data-id');
                var path = '{{ route('back.receipts.show') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        receipt_id: receipt_id,
                        _token: token
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if(data['status'] == false){
                            toastr.error(data['error']);
                        }else{
                            var viewUrl = '{{ route('back.receipts.view', $organization->id) }}';
                            // window.open(viewUrl);
                            window.location.href = viewUrl;
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
@endsection
