@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Invoices List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Invoices</li>
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
                            <table id="invoicesData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Order Total</th>
                                        <th>Prev Balance</th>
                                        <th>Subtotal</th>
                                        <th>Discount</th>
                                        <th>Total</th>
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

            find_invoices();
            function find_invoices() {
                var path = '{{ route('back.invoices.index', $organization->id) }}';
                $('#invoicesData').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": path,
                    "responsive": true,
                    "autoWidth": false,
                    "columns": [
                        { data: 'invoice_date', name: 'invoice_date' },
                        { data: 'full_names', name: 'full_names' },
                        { data: 'order_total', name: 'order_total' },
                        { data: 'prev_balance', name: 'prev_balance' },
                        { data: 'subtotal', name: 'subtotal' },
                        { data: 'discount', name: 'discount' },
                        { data: 'total', name: 'total' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    "order": [[ 0, "desc" ]],
                });
            }

            $(document).on('click', '.viewInvoiceBtn', function(e) {
                e.preventDefault();
                var invoice_id = $(this).data('id');
                var path = '{{ route('back.invoices.show') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        invoice_id: invoice_id
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if(data['status'] == false){
                            toastr.error(data['error']);
                        }else{
                            var invoicePath = '{{ route('back.invoices.view', $organization->id) }}';
                            setTimeout(function() {
                                window.location.href = invoicePath;
                            }, 1000);

                        }
                    }
                });
            });
        });
    </script>
@endsection
