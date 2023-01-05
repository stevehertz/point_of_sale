@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Products List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ url('back/products') }}/{{ $organization->id }}/create" class="btn btn-info">
                                    <i class="fa fa-plus"></i> NEW PRODUCT
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-10">
                            <table id="productsData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Code</th>
                                        <th>Brand</th>
                                        <th>Price</th>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                        <th>View</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(function() {

            find_products();

            function find_products() {
                var path = "{{ url('back/products') }}/{{ $organization->id }}";
                $("#productsData").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: path,
                    "autoWidth": false,
                    "responsive": true,
                    columns: [
                        {
                            data: 'product',
                            name: 'product'
                        },
                        {
                            data: 'product_type',
                            name: 'product_type'
                        },
                        {
                            data: 'category_name',
                            name: 'category_name'
                        },
                        {
                            data: 'product_code',
                            name: 'product_code'
                        },
                        {
                            data: 'brand_name',
                            name: 'brand_name'
                        },
                        {
                            data: 'selling_price',
                            name: 'selling_price'
                        },
                        {
                            data: 'short_name',
                            name: 'short_name'
                        },
                        {
                            data: 'stocks',
                            name: 'stocks'
                        },
                        {
                            data: 'view',
                            name: 'view',
                            orderable: false,
                            searchable: false
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

            $(document).on('click', '.deleteBtn', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this products!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var product_id = $(this).attr('id');
                        var path = '{{ route('back.products.delete') }}';
                        var token = '{{ csrf_token() }}';
                        $.ajax({
                            url: path,
                            type: "POST",
                            data: {
                                product_id: product_id,
                                _token: token,
                            },
                            dataType: "json",
                            success: function(data) {
                                if (data['status']) {
                                    Swal.fire(data['message'], '', 'success');
                                    $('#productsData').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(data['error'], '', 'error');
                                }
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info');
                    }
                });
            });

            $(document).on('click', '.viewBtn', function(e){
                e.preventDefault();
                var product_id = $(this).attr('id');
                var path = '{{ route('back.products.show') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: {
                        product_id: product_id,
                        _token: token,
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data['status']){
                            var viewURL = '{{ route('back.products.view', $organization->id) }}';
                            window.location.href = viewURL;
                        }else{
                            toastr.error(data['error']);
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });

            });

            $(document).on('click', '.editBtn', function(e){
                e.preventDefault();
                var product_id = $(this).attr('id');
                var path = '{{ route('back.products.show') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: {
                        product_id: product_id,
                        _token: token,
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data['status']){
                            var editURL = '{{ route('back.products.edit', $organization->id) }}';
                            window.location.href = editURL;
                        }else{
                            toastr.error(data['error']);
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });

            });
        });
    </script>
@endsection
