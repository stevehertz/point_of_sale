@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Product</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.products.index', $organization->id) }}">
                                Products List
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Edit Product</li>
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
                        <form id="updateProductForm" role="form">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="updateProductId"
                                                name="product_id" placeholder="Enter name" value="{{ $product->id }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="hidden" name="organization_id" class="form-control"
                                                id="updateProductOrganizationId" placeholder="Enter product code"
                                                value="{{ $organization->id }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="updateProductType">Product Type</label>
                                            <select id="updateProductType" name="product_type" class="form-control select2"
                                                style="width: 100%;">
                                                <option selected="selected" disabled="disabled">Choose Product Type</option>
                                                <option @if ($product->product_type == 'product') selected="selected" @endif
                                                    value="product">
                                                    Product
                                                </option>
                                                <option @if ($product->product_type == 'service') selected = 'selected' @endif
                                                    value="service">
                                                    Service
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="updateProductCategory">Product Category</label>
                                            <select id="updateProductCategory" name="category_id"
                                                class="form-control select2" style="width: 100%;">
                                                <option selected="selected" disabled="disabled">Choose Product Category
                                                </option>
                                                @forelse ($categories as $category)
                                                    <option @if ($product->category_id == $category->id) selected="selected" @endif
                                                        value="{{ $category->id }}">
                                                        {{ $category->category }}
                                                    </option>
                                                @empty
                                                    <option disabled="disabled">No Category Found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="updateProductCode">Bar/ Qr Code</label>
                                            <input type="text" name="product_code" class="form-control"
                                                id="updateProductCode" value="{{ $product->product_code }}"
                                                placeholder="Enter product code">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="updateProductName">Product Name</label>
                                            <input type="text" name="product_name" class="form-control"
                                                id="updateProductName" value="{{ $product->product }}"
                                                placeholder="Enter product name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="updateProductBrand">Brand</label>
                                            <select id="updateProductBrand" name="brand_id" class="form-control select2"
                                                style="width: 100%;">
                                                <option selected="selected" disabled="disabled">Choose Brand</option>
                                                @forelse ($brands as $brand)
                                                    <option @if ($product->brand_id == $brand->id) selected="selected" @endif
                                                        value="{{ $brand->id }}">
                                                        {{ $brand->brand }}
                                                    </option>
                                                @empty
                                                    <option disabled="disabled">No Brands Found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="newPurchasePrice">Purchase Price</label>
                                            <input type="text" name="purchase_price" class="form-control"
                                                id="newPurchasePrice" value="{{ $product->purchase_price }}"
                                                placeholder="Enter purchase price">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="newSalePrice">Sale Price</label>
                                            <input type="text" name="selling_price" value="{{ $product->selling_price }}"
                                                class="form-control" id="newSalePrice" placeholder="Enter sale price">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="updateProductUnit">Product Unit</label>
                                            <select id="updateProductUnit" name="unit_id" class="form-control select2"
                                                style="width: 100%;">
                                                <option selected="selected" disabled="disabled">Choose Unit</option>
                                                @forelse ($units as $unit)
                                                    <option @if ($product->unit_id == $unit->id) selected="selected" @endif
                                                        value="{{ $unit->id }}">
                                                        {{ $unit->name }}
                                                    </option>
                                                @empty
                                                    <option disabled="disabled">No Units Found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="updateProductSaleUnit">Sale Unit</label>
                                                <select id="updateProductSaleUnit" name="sale_unit"
                                                    class="form-control select2" style="width: 100%;">
                                                    <option disabled="disabled" selected="selected">Choose Sale Unit
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="updateProductPurchaseUnit">Purchase Unit</label>
                                                <select id="updateProductPurchaseUnit" name="purchase_unit"
                                                    class="form-control select2" style="width: 100%;">
                                                    <option disabled="disabled" selected="selected">Choose Purchase Unit
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-9">
                                        <button type="submit" id="updateProductSubmitBtn"
                                            class="btn btn-primary btn-flat btn-block">
                                            UPDATE
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ url('back/products') }}/{{ $organization->id }}"
                                            class="btn btn-secondary btn-block">CANCEL</a>
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

            find_units();
            function find_units() {
                var unit_id = '{{ $product->unit_id }}';
                var token = '{{ csrf_token() }}';
                var path = '{{ route('back.units.show') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: {
                        unit_id: unit_id,
                        _token: token
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status']) {
                            // $('#salePurchaseUnitRow').fadeIn(800);
                            $('#updateProductSaleUnit').append($('<option>', {
                                value: data['unit']['name'],
                                text: data['unit']['name']
                            }));
                            $('#updateProductPurchaseUnit').append($('<option>', {
                                value: data['unit']['name'],
                                text: data['unit']['name']
                            }));
                            find_unit_names(data['unit']['id']);
                        } else {
                            $('#salePurchaseUnitRow').fadeOut(900);
                            $.each(data['errors'], function(key, value) {
                                toastr.error(data['value']);
                            });
                        }
                    }
                });
            }

            function find_unit_names(unit_id) {
                var token = '{{ csrf_token() }}';
                var path = '{{ route('back.units.base.unit') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: {
                        unit_id: unit_id,
                        _token: token
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status']) {
                            $.each(data['unit_names'], function(key, value) {
                                $('#updateProductSaleUnit').append($('<option>', {
                                    value: value['name'],
                                    text: value['name']
                                }));
                                $('#updateProductPurchaseUnit').append($('<option>', {
                                    value: value['name'],
                                    text: value['name']
                                }));
                            });
                        } else {
                            $('#salePurchaseUnitRow').fadeOut(900);
                            $.each(data['errors'], function(key, value) {
                                toastr.error(value);
                            });
                        }
                    }
                });
            }

            $('#updateProductForm').submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                var path = '{{ route('back.products.update') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#updateProductSubmitBtn').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('#updateProductSubmitBtn').html('UPDATE');
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            window.location.href =
                                '{{ url('back/products') }}/{{ $organization->id }}';
                        } else {
                            toastr.error(data['error']);
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                var input = '#new' + key;
                                $(input + '+span').text(value);
                                $(input).addClass('is-invalid');
                            });
                        }
                    }
                });

            });
        });
    </script>
@endsection
