@extends('auth.layouts.app')

@section('content')
    <p class="login-box-msg">Sign in to start your session</p>

    <form id="loginForm" method="post">
        @csrf
        <div class="input-group mb-3">
            <input type="email" name="email" id="loginEmail" class="form-control" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="loginRemember">
                    <label for="remember">
                        Remember Me
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
                <button type="submit" id="loginSubmitBtn" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    {{-- <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
            <i class="fa fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
            <i class="fa fa-google-plus mr-2"></i> Sign in using Google+
        </a>
    </div> --}}
    <!-- /.social-auth-links -->

    <p class="mb-1">
        <a href="{{ route('auth.forgot.index') }}">I forgot my password</a>
    </p>
    <p class="mb-0">
        <a href="{{ route('auth.register.index') }}" class="text-center">Register a new membership</a>
    </p>
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                $.ajax({
                    url: '{{ route('auth.login.store') }}',
                    type: "POST",
                    data: form_data,
                    dataType: "json",
                    beforeSend: function() {
                        $('#loginSubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#loginSubmitBtn').html('Sign In');
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
