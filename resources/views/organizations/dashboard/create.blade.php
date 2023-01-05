@extends('organizations.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Company/ Businesss</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('organizations.dashboard.index') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">New Company/ Businesss</li>
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
                    <div class="card card-default">
                        <div class="card-body">
                            <form id="organizationForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizationName">Organization</label>
                                            <input type="text" name="organization" class="form-control"
                                                id="organizationName" placeholder="Enter Organization Name">
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizationTagline">Tagline</label>
                                            <input type="text" name="tagline" class="form-control"
                                                id="organizationTagline" placeholder="Enter Organization Tagline">
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="organizationLogo">Logo</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="logo" class="custom-file-input"
                                                        id="organizationLogo">
                                                    <label class="custom-file-label" for="organizationLogo">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizationEmail">Email Address</label>
                                            <input type="email" name="email" class="form-control" id="organizationEmail"
                                                placeholder="Enter Email Address">
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizationPhone">Phone Number</label>
                                            <input type="text" name="phone" class="form-control" id="organizationPhone"
                                                placeholder="Enter Phone Number">
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizationAddress">Address</label>
                                            <input type="text" name="address" class="form-control"
                                                id="organizationAddress" placeholder="Enter Address">
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizationWebsite">Website</label>
                                            <input type="text" name="website" class="form-control"
                                                id="organizationWebsite" placeholder="Enter Website">
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <button type="submit" id="organizationSubmitBtn"
                                                class="btn btn-primary btn-block">
                                                Enter
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-3">
                                        <a href="{{ route('organizations.dashboard.index') }}" class="btn btn-block btn-danger">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {

            $('#organizationForm').submit(function(e) {
                e.preventDefault();
                var path = '{{ route('organizations.dashboard.create') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#organizationSubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#organizationSubmitBtn').html(
                            'Enter'
                        );
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            window.location.href =
                                '{{ route('organizations.dashboard.index') }}';
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });

        });
    </script>
@endsection
