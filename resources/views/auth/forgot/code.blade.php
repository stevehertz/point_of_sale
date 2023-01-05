@extends('auth.layouts.app')

@section('content')
    <p class="login-box-msg">
        You are only one step a way from your new password, enter verification code here.
    </p>

    <form id="verificationCodeForm" method="post">
        @csrf
        <input type="hidden" id="verificationCodeUserId" value="{{ $user->id }}" name="user_id" />
        <div class="input-group mb-3">
            <input type="text" name="code" id="verificationCode" class="form-control"
                placeholder="Enter Verification Code">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" id="verificationCodeSubmitBtn" class="btn btn-primary btn-block">
                    Verify
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    <p class="mt-3 mb-1">
        <a href="{{ route('auth.login.index') }}">Login</a>
    </p>
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {

            $('#verificationCodeForm').submit(function(e) {
                e.preventDefault();
                var form_data = $(this).serialize();
                $.ajax({
                    url: '{{ route('auth.forgot.code') }}',
                    type: "POST",
                    data: form_data,
                    dataType: "json",
                    beforeSend: function() {
                        $('#verificationCodeSubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#verificationCodeSubmitBtn').html('Verify');
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            window.location.href = '{{ url('auth/forgot/recover') }}/' + data[
                                'user_id'];
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });

        });
    </script>
@endsection
