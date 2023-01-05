@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Sale #{{ $sale->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.sales.index', $organization->id) }}">
                                Sales List
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Sale #{{ $sale->id }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <img src="{{ asset('storage/organizations') }}/{{ $organization->logo }}"
                                        class="img-circle img-size-32 mr-2" alt="Logo">
                                    {{ $organization->organization }}
                                    <small class="float-right">Date: {{ $sale->sales_date }}</small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->

                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                Customer:
                                <address>
                                    @if ($customer == 'walk-in')
                                        <strong>{{ $sale->customer_name }}</strong><br>
                                    @else
                                        <strong>{{ $sale->customer_name }}</strong><br>
                                        {{ $customer->address }}<br>
                                        Phone: {{ $customer->phone }}<br>
                                        Email: {{ $customer->email }}
                                    @endif
                                </address>
                            </div>
                            <!-- /.col -->

                            <div class="col-sm-4 invoice-col">
                                Organization:
                                <address>
                                    <strong>{{ $organization->organization }}</strong><br>
                                    {{ $organization->address }}<br>
                                    Phone: {{ $organization->phone }}<br>
                                    Email: {{ $organization->email }}
                                </address>
                            </div>
                            <!-- /.col -->

                            <div class="col-sm-4 invoice-col">
                                <b>Sale #: {{ $sale->id }}</b><br>
                                <br>
                                <b>Sale Date:</b> {{ $sale->sales_date }}<br>
                                <b>Payment Status</b>
                                @if ($sale->payment_status == 1)
                                    <span class="badge badge-success">Paid</span>
                                @else
                                    <span class="badge badge-danger">Unpaid</span>
                                @endif
                                <br>
                                <b>Status:</b>
                                @if ($sale->sale_status == 1)
                                    <span class="badge badge-success">Complete</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </div>
                            <!-- /.col -->

                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-border table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Sale Price</th>
                                            <th>Quantity</th>
                                            <th>Item Total</th>
                                            @if ($sale->payment_status == 0)
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($sale_products as $product)
                                            <tr>
                                                <td>{{ $product->product->product }}</td>
                                                <td>{{ number_format($product->sale_price, 2, '.', ',') }}</td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>{{ number_format($product->total_price, 2, '.', ',') }}</td>
                                                @if ($sale->payment_status == 0)
                                                    <td>
                                                        <a href="#" data-id="{{ $product->id }}"
                                                            data-path='{{ route('back.sale.products.delete') }}'
                                                            data-token="{{ csrf_token() }}"
                                                            class="btn btn-danger btn-sm removeBtn">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No Products added yet</td>
                                            </tr>
                                        @endforelse
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-md-6 col-12">
                                @if ($sale->sale_status == 0)
                                    <button data-id="{{ $sale->id }}" data-token='{{ csrf_token() }}'
                                        data-path='{{ route('back.sales.show') }}'
                                        class="btn btn-sm btn-outline-primary newProductBtn">
                                        <i class="fa fa-plus"></i> Add a Product
                                    </button>
                                @endif
                                <br>

                                <br>
                                {{-- <p class="lead">Discount:</p> --}}
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6 col-12">
                                <p class="lead">
                                    Amount Due {{ $sale->sales_date }}
                                </p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Order Total:</th>
                                            <td>{{ number_format($sale->order_total, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">
                                                Sale Tax
                                            </th>
                                            <td>
                                                {{ number_format($sale->sale_tax, 2, '.', ',') }}({{ $sale->tax }}%)
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Discount:</th>
                                            <td>
                                                {{ number_format($sale->discount, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Shipping:</th>
                                            <td>
                                                {{ number_format($sale->shipping, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Subtotal</th>
                                            <td>{{ number_format($sale->subtotal, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Prev Balance</th>
                                            <td>{{ number_format($sale->prev_balance, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>{{ number_format($sale->total, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Paid:</th>
                                            <td>{{ number_format($sale->paid, 2, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Balance:</th>
                                            <td>{{ number_format($sale->balance, 2, '.', ',') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <form id="issueDiscountForm">
                                    @csrf
                                    <div class="form-group">
                                        <input type="hidden" value="{{ $organization->id }}"
                                            id="issueDiscountOrganizationId" class="form-control" name="organization_id" />
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" value="{{ $sale->id }}" id="issueDiscountSaleId"
                                            class="form-control" name="sale_id" />
                                    </div>
                                    <div class="form-group">
                                        <label for="issueDiscount">Discount</label>
                                        <input type="number" id="issueDiscount" class="form-control" name="discount"
                                            placeholder="Enter Discount">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="issueDiscountSubmitBtn"
                                            class="btn btn-block btn-success">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-4">
                                <form id="issueTaxForm">
                                    @csrf
                                    <div class="form-group">
                                        <input type="hidden" value="{{ $organization->id }}" id="issueTaxOrganizationId"
                                            class="form-control" name="organization_id" />
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" value="{{ $sale->id }}" id="issueTaxSaleId"
                                            class="form-control" name="sale_id" />
                                    </div>
                                    <div class="form-group">
                                        <label for="">Order Tax</label>
                                        <input type="number" class="form-control" name="tax" id="issueTax"
                                            placeholder="Enter Tax">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="issueTaxSubmitBtn" class="btn btn-block btn-primary">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                </form>

                            </div>
                            <div class="col-12 col-md-4">
                                <form id="issueShippingForm" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input type="hidden" value="{{ $organization->id }}"
                                            id="issueShippingOrganizationId" class="form-control"
                                            name="organization_id" />
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" value="{{ $sale->id }}" id="issueShippingSaleId"
                                            class="form-control" name="sale_id" />
                                    </div>
                                    <div class="form-group">
                                        <label for="issueShipping">Shipping</label>
                                        <input type="number" id="issueShipping" class="form-control" name="shipping"
                                            placeholder="Enter Shipping">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="issueShippingSubmitBtn"
                                            class="btn btn-block btn-warning">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>
                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                @if ($sale->payment_status == 0)
                                    <button type="button" data-id="{{ $sale->id }}"
                                        data-path='{{ route('back.sales.show') }}' data-token='{{ csrf_token() }}'
                                        class="btn btn-success btn-block updatePaymentsBtn">
                                        <i class="fa fa-credit-card"></i> Pay Now
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.invoice -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->

        <div class="modal fade" id="newProductModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Product</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="newProductForm">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" id="newProductOrganizationId" name="organization_id"
                                    class="form-control" />
                            </div>
                            <!-- /.form-gr  oup -->

                            <div class="form-group">
                                <input type="hidden" id="newProductSaleId" name="sale_id" class="form-control" />
                            </div>
                            <!-- /.form-gr  oup -->

                            <div class="form-group">
                                <label for="newProductName">Product</label>
                                <select name="product_id" class="form-control select2 select2-purple"
                                    data-dropdown-css-class="select2-purple" style="width: 100%;">
                                    <option disabled="disabled" selected="selected">
                                        Scan/Search Product By Code Name
                                    </option>
                                    @forelse ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->product_code }} ({{ $product->product }})
                                        </option>
                                    @empty
                                        <option disabled="disabled">No Product Added</option>
                                    @endforelse
                                </select>
                            </div>
                            <!-- /.form-gr  oup -->
                            <small>
                                <a href="{{ route('back.products.create', $organization->id) }}">
                                    <i class="fa fa-plus-circle"></i> Add Product
                                </a>
                            </small>

                            <div class="form-group">
                                <label for="newProductQuantity">Quantity</label>
                                <input type="text" id="newProductQuantity" name="quantity"
                                    placeholder="Enter Quantity" class="form-control" />
                            </div>
                            <!-- /.form-group -->
                        </div><!-- /.modal-body -->
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                            <button type="submit" id="newProductSubmitBtn" class="btn btn-primary">
                                <i class="fa fa-check"></i> SUBMIT PRODUCT
                            </button>
                        </div><!-- /.modal-footer -->
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="updatePaymentsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Make Payments</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="updatePaymentsForm">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" id="updatePaymentsSaleId" name="sale_id" class="form-control" />
                            </div>
                            <!-- /.form-gr  oup -->

                            <div class="form-group">
                                <label for="updatePaymentsAmount">Amount</label>
                                <input type="text" id="updatePaymentsAmount" name="amount"
                                    placeholder="Enter Amount" class="form-control" readonly />
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                <label for="updatePaymentsPaymentMethod">Payment Method</label>
                                <select id="newProductName" name="payment_method_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option disabled="disabled" selected="selected">Choose Product</option>
                                    @forelse ($payment_methods as $method)
                                        <option value="{{ $method->id }}">{{ $method->method }}</option>
                                    @empty
                                        <option disabled="disabled">No Payment Method Found</option>
                                    @endforelse
                                </select>
                                <small>
                                    <a href="{{ route('back.settings.index', $organization->id) }}">
                                        <i class="fa fa-plus-circle"></i> Add Product
                                    </a>
                                </small>
                            </div>
                            <!-- /.form-gr  oup -->

                            <div class="form-group">
                                <label for="updatePaymentsPaidAmount">Paid Amount</label>
                                <input type="text" id="updatePaymentsPaidAmount" name="paid"
                                    placeholder="Enter Amount" class="form-control" />
                            </div>
                            <!-- /.form-group -->


                        </div><!-- /.modal-body -->
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                            <button type="submit" id="updatePaymentsSubmitBtn" class="btn btn-primary">
                                <i class="fa fa-check"></i> PAY
                            </button>
                        </div><!-- /.modal-footer -->
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.newProductBtn', function(e) {
                e.preventDefault();
                var sales_id = $(this).data('id');
                var token = $(this).data('token');
                var path = $(this).data('path');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        sales_id: sales_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            $('#newProductOrganizationId').val(data['data']['organization_id']);
                            $('#newProductSaleId').val(data['data']['id']);
                            $('#newProductModal').modal('show');
                        }
                    }
                });
            });

            $('#newProductForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var path = '{{ route('back.sale.products.store') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: formData,
                    dataType: "json",
                    beforeSend: function() {
                        $('#newProductSubmitBtn').html(
                            '<i class="fa fa-spinner fa-spin"></i'
                        );
                        $('#newProductSubmitBtn').attr('disabled', true);
                    },
                    complete: function() {
                        $('#newProductSubmitBtn').html(
                            '<i class="fa fa-check"></i> SUBMIT PRODUCT'
                        );
                        $('#newProductSubmitBtn').attr('disabled', false);
                    },
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            // toastr.success(data['message']);
                            $('#newProductModal').modal('hide');
                            $('#newProductForm')[0].reset();
                            $('#newProductName').val('').trigger('change');
                            $('#newProductQuantity').val('');
                            find_product_stocks(data['sale_product_id']);

                        }
                    }
                });
            });

            function find_product_stocks(sale_product_id) {
                var path = '{{ route('back.products.update.stocks') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        sale_product_id: sale_product_id,
                        _token: token,
                    },
                    success: function(data) {
                        if (data['status']) {
                            update_stock(sale_product_id);
                        }
                    }

                });
            }

            function update_stock(sale_product_id) {
                var path = '{{ route('back.stocks.update') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        sale_product_id: sale_product_id,
                        _token: token,
                    },
                    success: function(data) {
                        if (data['status']) {
                            find_amount_update();
                        }
                    }

                });
            }

            $(document).on('click', '.removeBtn', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure, you want to remove this product from sale?',
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    // denyButtonText: `Don't save`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var path = $(this).data('path');
                        var token = $(this).data('token');
                        var sale_product_id = $(this).data('id');
                        $.ajax({
                            url: path,
                            type: 'POST',
                            data: {
                                _token: token,
                                sale_product_id: sale_product_id
                            },
                            dataType: "json",
                            success: function(data) {
                                if (data['status'] == false) {
                                    toastr.error(data['error']);
                                } else {
                                    find_product_deleted_stocks(data[
                                        'sale_product_id']);
                                }
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info');
                    }
                });


            });

            function find_product_deleted_stocks(sale_product_id) {
                var path = '{{ route('back.products.update.deleted.stocks') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        sale_product_id: sale_product_id,
                        _token: token,
                    },
                    success: function(data) {
                        if (data['status']) {
                            update_deleted_stock(sale_product_id);
                        }
                    }

                });
            }

            function update_deleted_stock(sale_product_id) {
                var path = '{{ route('back.stocks.update.deleted') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        sale_product_id: sale_product_id,
                        _token: token,
                    },
                    success: function(data) {
                        if (data['status']) {
                            find_amount_update();
                        }
                    }

                });
            }

            function find_amount_update() {
                var sale_id = '{{ $sale->id }}';
                var path = '{{ route('back.sales.update.total') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        sale_id: sale_id,
                        _token: token,
                    },
                    success: function(data) {
                        if (data['status']) {
                            location.reload();
                        } else {
                            toastr.error(data['error']);
                        }
                    }

                });
            }

            $('#issueDiscountForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var path = '{{ route('back.sales.update.discount') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: formData,
                    dataType: "json",
                    beforeSend: function() {
                        $('#issueDiscountSubmitBtn').html(
                            '<i class="fa fa-spinner fa-spin"></i'
                        );
                        $('#issueDiscountSubmitBtn').attr('disabled', true);
                    },
                    complete: function() {
                        $('#issueDiscountSubmitBtn').html(
                            '<i class="fa fa-save"></i> Save'
                        );
                        $('#issueDiscountSubmitBtn').attr('disabled', false);
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            location.reload();
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });

            $('#issueTaxForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var path = '{{ route('back.sales.update.tax') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: formData,
                    dataType: "json",
                    beforeSend: function() {
                        $('#issueTaxSubmitBtn').html(
                            '<i class="fa fa-spinner fa-spin"></i'
                        );
                        $('#issueTaxSubmitBtn').attr('disabled', true);
                    },
                    complete: function() {
                        $('#issueTaxSubmitBtn').html(
                            '<i class="fa fa-save"></i> Save'
                        );
                        $('#issueTaxSubmitBtn').attr('disabled', false);
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            location.reload();
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });

            $('#issueShippingForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var path = '{{ route('back.sales.update.shipping') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: formData,
                    dataType: "json",
                    beforeSend: function() {
                        $('#issueShippingSubmitBtn').html(
                            '<i class="fa fa-spinner fa-spin"></i'
                        );
                        $('#issueShippingSubmitBtn').attr('disabled', true);
                    },
                    complete: function() {
                        $('#issueShippingSubmitBtn').html(
                            '<i class="fa fa-save"></i> Save'
                        );
                        $('#issueShippingSubmitBtn').attr('disabled', false);
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            location.reload();
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });

            $(document).on('click', '.updatePaymentsBtn', function(e) {
                e.preventDefault();
                var path = $(this).data('path');
                var token = $(this).data('token');
                var sales_id = $(this).data('id');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        sales_id: sales_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            $('#updatePaymentsSaleId').val(data['data']['id']);
                            $('#updatePaymentsAmount').val(data['data']['total']);
                            $('#updatePaymentsModal').modal('show');

                        }
                    }
                });

            });

            $('#updatePaymentsForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var path = '{{ route('back.sales.update.payments') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: formData,
                    dataType: "json",
                    beforeSend: function() {
                        $('#updatePaymentsSubmitBtn').html(
                            '<i class="fa fa-spinner fa-spin"></i'
                        );
                        $('#updatePaymentsSubmitBtn').attr('disabled', true);
                    },
                    complete: function() {
                        $('#updatePaymentsSubmitBtn').html(
                            '<i class="fa fa-save"></i> Save'
                        );
                        $('#updatePaymentsSubmitBtn').attr('disabled', false);
                    },
                    success: function(data) {
                        if (data['status']) {
                            $('#updatePaymentsModal').modal('hide');
                            store_receipt();
                        } else {
                            toastr.error(data['error']);
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        $.each(errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                });
            });


            function store_receipt() {
                var sale_id = '{{ $sale->id }}';
                var path = '{{ route('back.receipts.store') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        sale_id: sale_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status']) {
                            var receipt_id = data['receipt_id'];
                            find_receipts(receipt_id);
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            }

            function find_receipts(receipt_id) {
                var path = '{{ route('back.receipts.show') }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        receipt_id: receipt_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            var receipt_url = '{{ route('back.receipts.view', $organization->id) }}';
                            window.location.href = receipt_url;
                        }
                    }
                });
            }

        });
    </script>
@endsection
