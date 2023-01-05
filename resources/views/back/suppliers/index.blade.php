@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Suppliers List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/suppliers') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Suppliers</li>
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
                                <a href="{{ url('back/suppliers') }}/{{ $organization->id }}/create"
                                    class="btn btn-info">
                                    <i class="fa fa-plus"></i> NEW SUPPLIER
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-10">
                            <table id="suppliersData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Full Names</th>
                                        <th>Phone Number</th>
                                        <th>Email Address</th>
                                        <th>Address</th>
                                        <th>Balance</th>
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

            find_suppliers();

            function find_suppliers() {
                var path = "{{ url('back/suppliers') }}/{{ $organization->id }}";
                $("#suppliersData").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: path,
                    "autoWidth": false,
                    "responsive": true,
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

            $(document).on('click', '.updateBtn', function(e) {
                e.preventDefault();
                var supplier_id = $(this).attr('id');
                var path = '{{ route('back.suppliers.show') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: {
                        supplier_id: supplier_id,
                        _token: token,
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            var edit_path = '{{ route('back.suppliers.edit', $organization->id) }}';
                            window.location.href = edit_path;
                        }
                    }
                });
            });

            $(document).on('click', '.deleteBtn', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure, you want to remove this supplier?',
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    // denyButtonText: `Don't save`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var supplier_id = $(this).attr('id');
                        var path = '{{ route('back.suppliers.delete') }}';
                        var token = '{{ csrf_token() }}';
                        $.ajax({
                            url: path,
                            type: "POST",
                            data: {
                                supplier_id: supplier_id,
                                _token: token,
                            },
                            dataType: "json",
                            success: function(data) {
                                if (data['status']) {
                                    Swal.fire(data['message'], '', 'success')
                                    $('#suppliersData').DataTable().ajax.reload();
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
