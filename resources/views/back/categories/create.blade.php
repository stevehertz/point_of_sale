@extends('back.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Category</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">New Category</li>
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
                        <form id="newCategoryForm" role="form">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="newCategoryOrganizationId" name="organization_id"
                                        value="{{ $organization->id }}" class="form-control"
                                        placeholder="Enter Category Name">
                                </div>
                                <div class="form-group">
                                    <label for="newCategoryName">Category Name</label>
                                    <input type="text" id="newCategoryName" name="category" class="form-control"
                                        placeholder="Enter Category Name">
                                </div>
                                <div class="form-group">
                                    <label for="newCategoryStatus">Status</label>
                                    <select name="status" id="newCategoryStatus" class="form-control select2"
                                        style="width: 100%;">
                                        <option selected="selected" disabled="disabled">Choose Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-9">
                                        <button type="submit" id="newCategorySubmitBtn" class="btn btn-primary btn-flat btn-block">
                                            CREATE
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ url('back/categories') }}/{{ $organization->id }}" class="btn btn-secondary btn-block">
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
            $('#newCategoryForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var path = '{{ route('back.categories.store') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#newCategorySubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#newCategorySubmitBtn').html('CREATE');
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            window.location.href =
                                '{{ url('back/categories') }}/{{ $organization->id }}';
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });

            });
        });
    </script>
@endsection
