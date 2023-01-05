@extends('auth.layouts.app')

@section('content')
    <p class="login-box-msg">Register a new membership</p>

    <form id="registrationForm" method="get">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="first_name" id="registrationFirstName" class="form-control" placeholder="First Name">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-user"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" id="registrationLastName" class="form-control" name="last_name" placeholder="Last Name">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-user"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="email" id="registrationEmail" name="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" id="registrationPhone" name="phone" class="form-control" placeholder="Phone Number">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-phone"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" id="registrationPassword" class="form-control" placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" id="registrationConfirmPassword" name="confirm_password" class="form-control"
                placeholder="Retype password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-7">
                &nbsp;
            </div>
            <!-- /.col -->
            <div class="col-5">
                <button type="submit" id="registrationSubmintBtn" class="btn btn-primary btn-block">Register</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    {{-- <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-primary">
                <i class="fab fa-facebook mr-2"></i>
                Sign up using Facebook
            </a>
            <a href="#" class="btn btn-block btn-danger">
                <i class="fab fa-google-plus mr-2"></i>
                Sign up using Google+
            </a>
        </div> --}}

    <a href="/" class="text-center">I already have a membership</a>
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {

            $('#registrationForm').submit(function(e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                $.ajax({
                    url: '{{ route('auth.register.store') }}',
                    type: "POST",
                    data: form_data,
                    dataType: "json",
                    beforeSend: function() {
                        $('#registrationSubmintBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#registrationSubmintBtn').html('Register');
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            window.location.href = '{{ route('organizations.dashboard.index') }}';
                        }else{
                            toastr.error(data['error']);
                        }
                    }
                });

            });

        });
    </script>
@endsection
