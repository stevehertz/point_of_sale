@extends('auth.layouts.app')

@section('content')
    <p class="login-box-msg">Register your Organization or Business</p>

    <form id="registrationOrganizationForm">
        <div class="input-group mb-3">
            <input type="text" name="organization" id="registrationOrganizationName" class="form-control"
                placeholder="Business / Organization Name">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-building"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" name="tagline" id="registrationOrganizationTagline" class="form-control"
                placeholder="Tagline">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-bookmark"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="email" id="registrationOrganizationEmail" name="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" id="registrationOrganizationPhone" name="phone" class="form-control"
                placeholder="Phone Number">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-phone"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" name="address" id="registrationOrganizationAddress" class="form-control"
                placeholder="Address">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-map-pin"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" id="registrationOrganizationWebsite" name="website" class="form-control"
                placeholder="Website">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fa fa-chrome"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-9">
                <button type="submit" class="btn btn-primary btn-block">Enter</button>
            </div>
            <!-- /.col -->
            <div class="col-3">
                <a href="#" class="btn btn-link">Logout</a>
            </div>
            <!-- /.col -->
        </div>
    </form>
@endsection
