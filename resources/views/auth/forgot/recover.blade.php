@extends('auth.layouts.app')

@section('content')
    <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

    <form id="recoverPasswordForm" method="post">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}" id="recoverPasswordUserId"/>
        <div class="input-group mb-3">
            <input type="password" id="recoverNewPassword" name="new_password" class="form-control" placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" id="recoverConfirmPassword" name="confirm_password" class="form-control"
                placeholder="Confirm Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" id="recoverPasswordSubmitBtn" class="btn btn-primary btn-block">
                    Change password
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    <p class="mt-3 mb-1">
        <a href="login.html">Login</a>
    </p>
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {
            $('#recoverPasswordForm').submit(function(e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                $.ajax({
                    url: '{{ route('auth.forgot.recover') }}',
                    type: "POST",
                    data: form_data,
                    dataType: "json",
                    beforeSend: function() {
                        $('#recoverPasswordSubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#recoverPasswordSubmitBtn').html('Change password');
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            window.location.href = '{{ route('auth.login.index') }}';
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });
        });
    </script>
@endsection
