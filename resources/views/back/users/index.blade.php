@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">User Profile</li>
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
                                    src="{{ asset('storage/users') }}/{{ Auth::user()->profile }}" alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>

                            <p class="text-muted text-center">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fa fa-phone mr-1"></i> Phone Number</strong>

                            <p class="text-muted">
                                {{ Auth::user()->phone}}
                            </p>

                            <hr>

                            <strong><i class="fa fa-user-plus mr-1"></i> Gender</strong>

                            <p class="text-muted">{{ Auth::user()->gender }}</p>

                            <hr>

                            <strong><i class="fa fa-calendar mr-1"></i> Date of Birth</strong>

                            <p class="text-muted">
                                <span class="tag tag-danger">{{ Auth::user()->dob }}</span>
                            </p>
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
                                    <a class="nav-link active" href="#accountTab" data-toggle="tab">
                                        User Acccount
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#changePasswordTab" data-toggle="tab">
                                        Change Password
                                    </a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="accountTab">
                                    <form id="updateAccountForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="accountFirstName">First Name</label>
                                                    <input type="text" class="form-control" name="first_name"
                                                        id="accountFirstName" value="{{ Auth::user()->first_name }}" placeholder="Enter First Names">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="accountLastName">Last Name</label>
                                                    <input type="text" class="form-control" name="last_name"
                                                        id="accountLastName" value="{{ Auth::user()->last_name }}" placeholder="Enter Last Names">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="accountProfile">Profile</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="profile" class="custom-file-input"
                                                                id="accountProfile">
                                                            <label class="custom-file-label" for="accountProfile">Choose
                                                                file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="accountGender">Gender</label>
                                                    <select name="gender" class="form-control select2" style="width: 100%;"
                                                        id="accountGender">
                                                        <option disabled="disabled" @if(Auth::user()->gender == null) selected="selected" @endif>Choose Gender</option>
                                                        <option value="Male" @if (Auth::user()->gender == "Male")
                                                            selected="selected"
                                                        @endif >Male</option>
                                                        <option value="Female" @if (Auth::user()->gender == "Female")
                                                            selected="selected"
                                                        @endif>Female</option>
                                                    </select>
                                                </div>
                                                <!-- /.form-gr  oup -->
                                            </div>

                                            <div class="col-md-6">
                                                <!-- textarea -->
                                                <div class="form-group">
                                                    <label for="accountDOB">Date of Birth</label>
                                                    <input type="text" class="form-control datepicker" name="dob"
                                                        id="accountDOB" value="{{ Auth::user()->dob }}" placeholder="Enter Date of Birth">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" id="updateAccountSubmitBtn" class="btn btn-info btn-block btn-flat">
                                                        UPDATE
                                                    </button>
                                                </div>
                                            </div>


                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="changePasswordTab">
                                    <form id="changePasswordForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="changeCurrentPassword">Current Password</label>
                                                    <input type="password" class="form-control" name="current_password"
                                                        id="changeCurrentPassword" placeholder="Current Password">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="changeNewPassword">New Password</label>
                                                    <input type="password" class="form-control" name="new_password"
                                                        id="changeNewPassword" placeholder="New Password">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="changeConfirmPassword">Confirm Password</label>
                                                    <input type="password" class="form-control" name="confirm_password"
                                                        id="changeConfirmPassword" placeholder="Confirm Password">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" id="changePasswordSubmitBtn" class="btn btn-info btn-block btn-flat">UPDATE PASSWORD</button>
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
    $(document).ready(function(){

        $('#updateAccountForm').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            var path = "{{ route('back.profile.update') }}";
            $.ajax({
                url: path,
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend:function(){
                    $('#updateAccountSubmitBtn').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete:function() {
                    $('#updateAccountSubmitBtn').html('UPDATE');
                },
                success:function(data){
                    if(data['status']){
                        toastr.success(data['message']);
                        location.reload();
                    }else{
                        toastr.error(data['error']);
                    }
                }
            });
        });

        $('#changePasswordForm').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            var path = '{{ route('back.profile.update.password') }}';
            $.ajax({
                url: path,
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend:function(){
                    $('#changePasswordSubmitBtn').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete:function() {
                    $('#changePasswordSubmitBtn').html('UPDATE PASSWORD');
                },
                success:function(data){
                    if(data['status']){
                        toastr.success(data['message']);
                        location.reload();
                    }else{
                        toastr.error(data['error']);
                    }
                }
            });

        });
    })
</script>

@endsection
