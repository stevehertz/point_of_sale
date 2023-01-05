@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View {{ $product->product }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.products.index', $organization->id) }}">Products List</a>
                        </li>
                        <li class="breadcrumb-item active">View Product</li>
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
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Product Code</th>
                                        <td>{{ $product->product_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Barcode</th>
                                        <td>{!! $product->barcodes !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $product->product }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td>{{ $category->category }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td>{{ $product->product_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Brand</th>
                                        <td>{{ $brand->brand }}</td>
                                    </tr>
                                    <tr>
                                        <th>Purchase Price</th>
                                        <td>{{ $product->purchase_price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Selling Price</th>
                                        <td>{{ $product->selling_price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Unit</th>
                                        <td>{{ $unit->short_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Purchase Unit</th>
                                        <td>{{ $product->purchase_unit }}</td>
                                    </tr>
                                    <tr>
                                        <th>Selling Unit</th>
                                        <td>{{ $product->sale_unit }}</td>
                                    </tr>
                                    <tr>
                                        <th>Available Stocks</th>
                                        <td>{{ $product->stocks }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="{{ route('back.products.index', $organization->id) }}"
                                        class="btn btn-secondary btn-block">
                                        <i class="fa fa-arrow-left"></i> BACK
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#" target="_blank" data-id='{{ $product->id }}' class="btn btn-block btn-primary printProduct">
                                        Print <i class="fa fa-print"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
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
        $(document).ready(function() {

            $(document).on('click', '.printProduct', function(e){
                e.preventDefault();
                var product_id = $(this).data('id');
                var path = "{{ route('back.products.show') }}";
                var token = "{{ csrf_token() }}";
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        product_id: product_id,
                    },
                    success: function(data) {
                        if(data['status']) {
                            var url = '{{ route('back.products.print', $organization->id) }}';
                            window.open(url, '_blank');
                        }else{
                            toastr.error(data['errors']);
                        }
                    }
                });
            });

        });
    </script>
@endsection
