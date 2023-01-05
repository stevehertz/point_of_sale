@extends('auth.layouts.app')


@section('content')
    <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

    <form id="forgotPassForm" method="post">
        @csrf
        <div class="input-group mb-3">
            <input type="email" name="email" id="forgotPassEmail" class="form-control" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" id="forgotPassSubmitBtn" class="btn btn-primary btn-block">
                    Request new password
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    <p class="mt-3 mb-1">
        <a href="{{ route('auth.login.index') }}">Login</a>
    </p>
    <p class="mb-0">
        <a href="{{ route('auth.register.index') }}" class="text-center">Register a new membership</a>
    </p>
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {
            $('#forgotPassForm').submit(function(e){
                e.preventDefault();
                var form_data = $(this).serialize();
                $.ajax({
                    url: '{{ route('auth.forgot.store') }}',
                    type: "POST",
                    data: form_data,
                    dataType: "json",
                    beforeSend: function() {
                        $('#forgotPassSubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#forgotPassSubmitBtn').html('Request new password');
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            window.location.href = '{{ url('auth/forgot/code') }}/'+data['user_id'];
                        }else{
                            toastr.error(data['error']);
                        }
                    }
                });
            });
        });
    </script>
@endsection
