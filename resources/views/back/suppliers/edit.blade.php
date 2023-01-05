@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Supplier</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Supplier</li>
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
                        <form id="updateSupplierForm" role="form">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="updateSupplierId" name="supplier_id"
                                        value="{{ $supplier->id }}" class="form-control" placeholder="Enter Full Names">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" id="updateSupplierOrganizationId" name="organization_id"
                                        value="{{ $organization->id }}" class="form-control"
                                        placeholder="Enter Full Names">
                                </div>
                                <div class="form-group">
                                    <label for="updateSupplierFullNames">Full Names</label>
                                    <input type="text" id="updateSupplierFullNames" name="fullnames"
                                        value="{{ $supplier->full_names }}" class="form-control"
                                        placeholder="Enter Full Names">
                                </div>
                                <div class="form-group">
                                    <label for="updateSupplierPhone">Phone Number</label>
                                    <input type="text" id="updateSupplierPhone" value="{{ $supplier->phone }}"
                                        name="phone" class="form-control" placeholder="Enter Phone Number">
                                </div>
                                <div class="form-group">
                                    <label for="updateSupplierEmail">Email address</label>
                                    <input type="email" name="email" value="{{ $supplier->email }}"
                                        class="form-control" id="updateSupplierEmail" placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label for="updateSupplierAddress">Address</label>
                                    <textarea name="address" id="updateSupplierAddress" class="form-control" placeholder="Enter Address">{{ $supplier->address }}</textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-9">
                                        <button type="submit" id="updateSupplierSubmitBtn"
                                            class="btn btn-primary btn-flat btn-block">
                                            UPDATE
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ url('back/suppliers') }}/{{ $organization->id }}"
                                            class="btn btn-secondary btn-block">
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
            $('#updateSupplierForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var path = '{{ route('back.suppliers.update') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#updateSupplierSubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#updateSupplierSubmitBtn').html('UPDATE');
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
