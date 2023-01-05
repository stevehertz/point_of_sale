@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Customer</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">New Customer</li>
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
                        <form id="newCustomerForm" role="form">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="newCustomerOrganizationId" name="organization_id"
                                        value="{{ $organization->id }}" class="form-control">
                                </div>
                                <!-- /.form-group -->

                                <div id="newCustomerFullNamesRow" class="form-group">
                                    <label for="newCustomerFullNames">Full Names</label>
                                    <input type="text" id="newCustomerFullNames" name="full_names" class="form-control"
                                        placeholder="Enter Full Names">
                                </div>
                                <div id="newCustomerPhoneRow" class="form-group">
                                    <label for="newCustomerPhone">Phone Number</label>
                                    <input type="text" id="newCustomerPhone" name="phone" class="form-control"
                                        placeholder="Enter Phone Number">
                                </div>
                                <div id="newCustomerEmailRow" class="form-group">
                                    <label for="newCustomerEmail">Email address</label>
                                    <input type="email" name="email" class="form-control" id="newCustomerEmail"
                                        placeholder="Enter email">
                                </div>
                                <div id="newCustomerAddressRow" class="form-group">
                                    <label for="newCustomerAddress">Address</label>
                                    <input type="text" name="address" id="newCustomerAddress" class="form-control"
                                        placeholder="Enter Address" />
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-9">
                                        <button type="submit" id="newCustomerSubmitBtn"
                                            class="btn btn-primary btn-flat btn-block">
                                            Create</button>
                                    </div>

                                    <div class="col-md-3">
                                        <a href="{{ route('back.customers.index', $organization->id) }}"
                                            class="btn btn-secondary btn-block">
                                            Cancel</a>
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

            $('#newCustomerForm').submit(function(e) {
                e.preventDefault();
                $('#newCustomerSubmitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#newCustomerSubmitBtn').attr('disbled', true);
                var path = '{{ route('back.customers.store') }}';
                var formData = new FormData(this);
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data.message);
                            setTimeout(function() {
                                window.location.href =
                                    '{{ route('back.customers.index', $organization->id) }}';
                            }, 2000);
                        } else {
                            toastr.error(data['error']);
                            $('#newCustomerSubmitBtn').html('Create');
                            $('#newCustomerSubmitBtn').attr('disbled', false);

                        }
                    }
                });
            });
        });
    </script>
@endsection
