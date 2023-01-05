@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Invoice</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.invoices.index', $organization->id) }}">
                                Invoices
                            </a>
                        </li>
                        <li class="breadcrumb-item active">View Invoice</li>
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
                                        class="img-circle img-size-32 mr-2" alt="Logo"> {{ $organization->organization }}
                                    <small class="float-right">Issued Date: {{ $invoice->invoice_date }}</small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                From
                                <address>
                                    <strong>{{ $organization->organization }}</strong><br>
                                    {{ $organization->address }}<br>
                                    Phone: {{ $organization->phone }}<br>
                                    Email: {{ $organization->email }}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                To
                                <address>
                                    <strong>{{ $customer->full_names }}</strong><br>
                                    {{ $customer->address }}<br>
                                    Phone: {{ $customer->phone }}<br>
                                    Email: {{ $customer->email }}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice #{{ $invoice->id }}</b><br>
                                <br>
                                <b>Issued Date:</b> {{ $invoice->invoice_date }}<br>
                                <b>Payment Due:</b> {{ $invoice->due_date }}<br>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Quantity</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($invoice_products as $product)
                                            <tr>
                                                <td>{{ $product->quantity }}</td>
                                                <td>{{ $product->product->product }}</td>
                                                <td>{{ $product->price }}</td>
                                                <td>{{ $product->total }}</td>
                                                <td>
                                                    <a href="#" data-id='{{ $product->id }}' data-path='{{ route('back.invoice.products.delete') }}' data-token='{{ csrf_token() }}' class="btn btn-danger btn-sm removeProductBtn">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <p class="text-center">No products found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-6">
                                {{-- <p class="lead">Payment Methods:</p> --}}
                                <a href="#" data-id="{{ $invoice->id }}" data-token='{{ csrf_token() }}'
                                    data-path='{{ route('back.invoices.show') }}'
                                    class="btn btn-sm btn-outline-primary newProductBtn">
                                    <i class="fa fa-plus"></i> Add a Product
                                </a>
                                <br>
                                &nbsp;
                            </div>
                            <!-- /.col -->
                            <div class="col-6">
                                <p class="lead">Amount Due {{ $invoice->due_date }}</p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Order Total</th>
                                            <td>{{ number_format($invoice->order_total, 2, '.', ',') }}</td>
                                        </tr>

                                        <tr>
                                            <th>Discount:</th>
                                            <td>{{ number_format($invoice->discount, 2, '.', ',') }}</td>
                                        </tr>

                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td>{{ number_format($invoice->subtotal, 2, '.', ',') }}</td>
                                        </tr>

                                        <tr>
                                            <th>Prev Balance:</th>
                                            <td>{{ number_format($invoice->prev_balance, 2, '.', ',') }}</td>
                                        </tr>

                                        <tr>
                                            <th>Total:</th>
                                            <td>{{ number_format($invoice->total, 2, '.', ',') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="invoice-print.html" target="_blank" class="btn btn-default">
                                    <i class="fa fa-print"></i> Print
                                </a>
                                <button type="button" class="btn btn-primary" style="margin-right: 5px;">
                                    <i class="fa fa-download"></i> Generate PDF
                                </button>
                                <button type="button" class="btn btn-success float-right">
                                    <i class="fa fa-credit-card"></i> Create Sale
                                </button>
                                <button type="button" class="btn btn-info float-right" style="margin-right: 5px;">
                                    <i class="fa fa-send"></i> Send to Email
                                </button>

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
                                <input type="hidden" id="newProductInvoiceId" name="invoice_id" class="form-control" />
                            </div>
                            <!-- /.form-gr  oup -->

                            <div class="form-group">
                                <label for="newProductName">Product</label>
                                <select id="newProductName" name="product_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option disabled="disabled" selected="selected">Choose Product</option>
                                    @forelse ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product }}</option>
                                    @empty
                                        <option disabled="disabled">No Product Added</option>
                                    @endforelse
                                </select>
                                <small>
                                    <a href="{{ route('back.products.create', $organization->id) }}">
                                        <i class="fa fa-plus-circle"></i> Add Product
                                    </a>
                                </small>
                            </div>
                            <!-- /.form-gr  oup -->

                            <div class="form-group">
                                <label for="newProductQuantity">Quantity</label>
                                <input type="text" id="newProductQuantity" name="quantity" placeholder="Enter Quantity"
                                    class="form-control" />
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
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click', '.newProductBtn', function(e) {
                e.preventDefault();
                var invoice_id = $(this).data('id');
                var token = $(this).data('token');
                var path = $(this).data('path');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        invoice_id: invoice_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            $('#newProductOrganizationId').val(data['data']['organization_id']);
                            $('#newProductInvoiceId').val(data['data']['id']);
                            $('#newProductModal').modal('show');
                        }
                    }
                });
            });

            $('#newProductForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var path = '{{ route('back.invoice.products.store') }}';
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
                            toastr.success(data['message']);
                            find_amount_update();
                            $('#newProductModal').modal('hide');
                            $('#newProductForm')[0].reset();
                            $('#newProductName').val('').trigger('change');
                            $('#newProductQuantity').val('');
                            location.reload();
                        }
                    }
                });
            });

            function find_amount_update() {
                var path = '{{ route('back.invoices.update.amount') }}';
                var invoice_id = '{{ $invoice->id }}';
                var token = '{{ csrf_token() }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        invoice_id: invoice_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            toastr.success(data['message']);
                            location.reload();
                        }
                    }
                });
            }

            $(document).on('click', '.removeProductBtn', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure, you want to remove this product from invoice?',
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    // denyButtonText: `Don't save`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var path = $(this).data('path');
                        var token = $(this).data('token');
                        var invoice_product_id = $(this).data('id');
                        $.ajax({
                            url: path,
                            type: 'POST',
                            data: {
                                _token: token,
                                invoice_product_id: invoice_product_id
                            },
                            dataType: "json",
                            success: function(data) {
                                if (data['status'] == false) {
                                    toastr.error(data['error']);
                                } else {
                                    find_amount_update();
                                    Swal.fire(data['message'], '', 'success');
                                    location.reload();
                                }
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info');
                    }
                });

            });

        });
    </script>
@endsection
