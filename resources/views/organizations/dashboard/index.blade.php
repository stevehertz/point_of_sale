@extends('organizations.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">My Organizations</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('organizations.dashboard.index') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Organizations</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fa fa-cog"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">My Company/ Business</span>
                            <span class="info-box-number">
                                {{ $num_organizations }}
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('organizations.dashboard.create') }}" class="btn btn-info" style="color: white;">
                                    <i class="fa fa-plus"></i> New Company/ Business
                                </a>

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="organizationsData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Organization</th>
                                        <th>Tagline</th>
                                        <th>Logo</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div><!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(function() {

            find_organizations();
            function find_organizations() {
                $("#organizationsData").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('organizations.dashboard.index') }}",
                    "autoWidth": false,
                    "responsive": true,
                    columns: [{
                            data: 'organization',
                            name: 'organization'
                        },
                        {
                            data: 'tagline',
                            name: 'tagline'
                        },
                        {
                            data: 'logo',
                            name: 'logo'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            }

            $(document).on('click', '.selectBtn', function(e){
                e.preventDefault();
                var org_id = $(this).attr('id');
                var path = '{{ route("organizations.dashboard.show") }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url:path,
                    type:"POST",
                    data:{
                        organization_id:org_id,
                        _token:token,
                    },
                    dataType:"json",
                    success:function(data){
                        if(data['status'] == false){
                            toastr.error(data['error']);
                        }else {
                            window.location.href = '{{ url("back/dashboard/")}}/'+data['data']['id'];
                        }
                    }
                });
            });

        });
    </script>
@endsection
