@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard/') }}/{{ $organization->id }}">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{ asset('storage/organizations') }}/{{ $organization->logo }}" alt="Logo">
                            </div>

                            <h3 class="profile-username text-center">{{ $organization->organization }}</h3>

                            <p class="text-muted text-center">{{ $organization->email }}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fa fa-bookmark mr-1"></i> Tagline</strong>

                            <p class="text-muted">
                                {{ $organization->tagline }}
                            </p>

                            <hr>

                            <strong><i class="fa fa-bookmark mr-1"></i> Phone Number</strong>

                            <p class="text-muted">
                                {{ $organization->phone }}
                            </p>

                            <hr>
                            <strong><i class="fa fa-chrome mr-1"></i> Website</strong>

                            <p class="text-muted">
                                {{ $organization->website }}
                            </p>

                            <hr>

                            <strong><i class="fa fa-map-pin mr-1"></i> Address</strong>

                            <p class="text-muted">{{ $organization->address }}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#settingsTab" data-toggle="tab">
                                        Settings
                                    </a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="settingsTab">
                                    <form id="settingsOrganizationForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="hidden" name="organization_id"
                                                        value="{{ $organization->id }}" id="settingsOrganizationId"
                                                        class="form-control" placeholder="Business / Organization Name">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="settingsOrganizationName">Organiztion Name</label>
                                                    <input type="text" name="organization"
                                                        value="{{ $organization->organization }}"
                                                        id="settingsOrganizationName" class="form-control"
                                                        placeholder="Business / Organization Name">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="settingsOrganizationTagline">Tagline</label>
                                                    <input type="text" name="tagline"
                                                        value="{{ $organization->tagline }}"
                                                        id="settingsOrganizationTagline" class="form-control"
                                                        placeholder="Tagline">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="settingsOrganizationLogo">Logo</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="logo" class="custom-file-input"
                                                                id="settingsOrganizationLogo">
                                                            <label class="custom-file-label"
                                                                for="settingsOrganizationLogo">Choose
                                                                file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <!-- textarea -->
                                                <div class="form-group">
                                                    <label for="settingsOrganizationAddress">Address</label>
                                                    <textarea class="form-control" id="settingsOrganizationAddress" name="address"
                                                        placeholder="Enter Address">{{ $organization->address }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="settingsOrganizationWebsite">Website</label>
                                                    <input type="text" class="form-control" name="website"
                                                        value="{{ $organization->website }}"
                                                        id="settingsOrganizationWebsite" placeholder="Enter Website">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" id="settingsOrganizationSubmitBtn"
                                                        class="btn btn-info btn-block btn-flat">UPDATE</button>
                                                </div>
                                            </div>


                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
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

            $('#settingsOrganizationForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var path = '{{ route('back.settings.update') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#settingsOrganizationSubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#settingsOrganizationSubmitBtn').html('UPDATE');
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            location.reload();
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });
        });
    </script>
@endsection
