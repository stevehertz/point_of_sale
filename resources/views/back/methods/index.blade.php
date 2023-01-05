@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Payment Method List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">PAyment Methods</li>
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
                                <a href="#" class="btn btn-info" id="newMethodBtn">
                                    <i class="fa fa-plus"></i> NEW METHOD
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-10">
                            <table id="paymentMethodsData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Slug</th>
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

        <div class="modal fade" id="newMethodModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Payment Method</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="newMethodForm">
                        <div class="modal-body">
                            <div class="row">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" name="organization_id" id="newMethodOrganizationId"
                                            class="form-control" value="{{ $organization->id }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="newMethodName">Method</label>
                                        <input type="text" name="method" id="newMethodName" class="form-control"
                                            placeholder="Enter Payment Method Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                CLOSE
                            </button>
                            <button type="submit" class="btn btn-primary">
                                SAVE METHOD
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {

            find_payment_methods();

            function find_payment_methods() {
                var path = '{{ route('back.payments.methods.index', $organization->id) }}';
                $("#paymentMethodsData").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: path,
                    "autoWidth": false,
                    "responsive": true,
                    columns: [{
                            data: 'method',
                            name: 'method'
                        },
                        {
                            data: 'slug',
                            name: 'slug'
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

            $(document).on('click', '#newMethodBtn', function(e) {
                e.preventDefault();
                $('#newMethodModal').modal('show');
            });

            $('#newMethodForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var path = '{{ route('back.settings.payment.methods.store') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#newMethodForm').find('button[type="submit"]').html(
                            '<i class="fa fa-spin fa-spinner"></i>'
                        );
                    },
                    complete: function() {
                        $('#newMethodForm').find('button[type="submit"]').html(
                            'SAVE METHOD'
                        );
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            $('#newMethodModal').modal('hide');
                            $('#newMethodForm').trigger('reset');
                            $("#paymentMethodsData").DataTable().ajax.reload();
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });

            $(document).on('click', '.deleteBtn', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure, you want to remove this method?',
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    // denyButtonText: `Don't save`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var method_id = $(this).attr('id');
                        var path = '{{ route('back.settings.payment.methods.delete') }}';
                        var token = '{{ csrf_token() }}';
                        $.ajax({
                            url: path,
                            type: "POST",
                            data: {
                                method_id: method_id,
                                _token: token
                            },
                            dataType: "json",
                            beforeSend: function() {
                                $(this).html(
                                    '<i class="fa fa-spin fa-spinner"></i>'
                                );
                            },
                            complete: function() {
                                $(this).html(
                                    '<i class="fa fa-trash"></i>'
                                );
                            },
                            success: function(data) {
                                if (data['status']) {
                                    toastr.success(data['message']);
                                    $("#paymentMethodsData").DataTable().ajax.reload();
                                } else {
                                    toastr.error(data['error']);
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
