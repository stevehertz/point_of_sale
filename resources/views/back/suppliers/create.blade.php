@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Supplier</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">New Supplier</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-8 offset-md-2">
                    <!-- general form elements -->
                    <div class="card">
                        <!-- form start -->
                        <form id="newSupplierForm" role="form">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="newSupplierOrganizationId" name="organization_id"
                                        value="{{ $organization->id }}" class="form-control"
                                        placeholder="Enter Full Names">
                                </div>
                                <div class="form-group">
                                    <label for="newSupplierFullNames">Full Names</label>
                                    <input type="text" id="newSupplierFullNames" name="fullnames" class="form-control"
                                        placeholder="Enter Full Names">
                                </div>
                                <div class="form-group">
                                    <label for="newSupplierPhone">Phone Number</label>
                                    <input type="text" id="newSupplierPhone" name="phone" class="form-control"
                                        placeholder="Enter Phone Number">
                                </div>
                                <div class="form-group">
                                    <label for="newSupplierEmail">Email address</label>
                                    <input type="email" name="email" class="form-control" id="newSupplierEmail"
                                        placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label for="newSupplierAddress">Address</label>
                                    <textarea name="address" id="newSupplierAddress" class="form-control" placeholder="Enter Address"></textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-9">
                                        <button type="submit" id="newSupplierSubmitBtn"
                                            class="btn btn-primary btn-flat btn-block">
                                            CREATE
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ url('back/suppliers') }}/{{ $organization->id }}" class="btn btn-secondary btn-block">
                                            CANCEL
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {
            $('#newSupplierForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var path = '{{ route('back.suppliers.store') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#newSupplierSubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#newSupplierSubmitBtn').html('CREATE');
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            window.location.href =
                                '{{ url('back/suppliers') }}/{{ $organization->id }}';
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });

            });
        });
    </script>
@endsection
