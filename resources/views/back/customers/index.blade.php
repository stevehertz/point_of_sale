@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Customers List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Customers</li>
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
                                <a href="{{ route('back.customers.create', $organization->id) }}" class="btn btn-info">
                                    <i class="fa fa-plus"></i> NEW CUSTOMER
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-10">
                            <table id="customersData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Full Names</th>
                                        <th>Phone Number</th>
                                        <th>Email Address</th>
                                        <th>Address</th>
                                        <th>Action</th>
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

            find_customers();

            function find_customers() {
                var path = '{{ route('back.customers.index', $organization->id) }}'
                $('#customersData').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: path,
                    responsive: true,
                    autoWidth: false,
                    order: [
                        ['0', 'asc']
                    ],
                    columns: [{
                            data: 'full_names',
                            name: 'full_names'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }

            $(document).on('click', '.deleteCustomerBtn', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this customer!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var customer_id = $(this).data('id');
                        var path = '{{ route('back.customers.delete') }}';
                        var token = '{{ csrf_token() }}';
                        $.ajax({
                            url: path,
                            type: "POST",
                            data: {
                                customer_id: customer_id,
                                _token: token,
                            },
                            dataType: "json",
                            success: function(data) {
                                if (data['status']) {
                                    Swal.fire(data['message'], '', 'success');
                                    $('#customersData').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(data['error'], '', 'error');
                                }
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info');
                    }
                });

            });
        });
    </script>
@endsection
