@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Brand</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.brands.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">New Brand</li>
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
                        <form id="newBrandForm" role="form">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="newBrandOrganizationId" name="organization_id"
                                        value="{{ $organization->id }}" class="form-control"
                                        placeholder="Enter Category Name">
                                </div>
                                <div class="form-group">
                                    <label for="newBrandName">Brand Name</label>
                                    <input type="text" id="newBrandName" name="brand" class="form-control"
                                        placeholder="Enter Brand Name">
                                </div>
                                <div class="form-group">
                                    <label for="newBrandIcon">Icon</label>
                                    <div class="custom-file">
                                        <input type="file" name="icon" class="custom-file-input" id="newBrandIcon">
                                        <label class="custom-file-label" for="newBrandIcon">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-9">
                                        <button type="submit" id="newBrandSubmitBtn"
                                            class="btn btn-primary btn-flat btn-block">
                                            CREATE
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('back.brands.index', $organization->id) }}"
                                            class="btn btn-secondary btn-block">
                                            CANCEL
                                        </a>
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

            $('#newBrandForm').submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                var path = '{{ route('back.brands.store') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: formData,
                    beforeSend:function(){
                        $('#newBrandSubmitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
                        $('#newBrandSubmitBtn').attr('disabled', true);
                    },
                    complete:function(){
                        $('#newBrandSubmitBtn').html('CREATE');
                        $('#newBrandSubmitBtn').attr('disabled', false);
                    },
                    success: function(data){
                        if(data['status']){
                            toastr.success(data['message']);
                            setTimeout(function(){
                                window.location.href = '{{ route('back.brands.index', $organization->id) }}';
                            }, 1000);
                        }else{
                            toastr.error(data['error']);
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false,
                    error: function(data){
                        var errors = data.responseJSON;
                        if(errors){
                            $.each(errors, function(key, value){
                                toastr.error(value);
                            });
                        }
                    }
                });
            });

        });
    </script>
@endsection
