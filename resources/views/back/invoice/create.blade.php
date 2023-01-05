@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Invoice</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active">New Invoice</li>
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
                        <form id="newInvoiceForm" role="form" autocomplete="off">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="newInvoiceOrganizationId" name="organization_id"
                                        value="{{ $organization->id }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="newInvoiceCustomerId">Customer</label>
                                    <select id="newInvoiceCustomerId" name="customer_id" class="form-control select2"
                                        style="width: 100%;">
                                        <option selected="selected" disabled="disabled">Select Customer</option>
                                        @forelse ($customers as $customer)
                                            <option value="{{ $customer->id }}"> {{ $customer->full_names }} </option>
                                        @empty
                                            <option disabled="disabled">No Customers</option>
                                        @endforelse
                                    </select>
                                    <small>
                                        <a href="{{ url('back/customers/' . $organization->id . '/create') }}">
                                            <i class="fa fa-plus-circle"></i> Add Customer
                                        </a>
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="newInvoiceDate">Issued Date</label>
                                    <input type="text" name="invoice_date" placeholder="Enter Issued Date"
                                        id="newInvoiceDate" class="form-control datepicker">
                                </div>
                                <div class="form-group">
                                    <label for="newDueDate">Due Date</label>
                                    <input type="text" name="due_date" placeholder="Enter Due Date" id="newDueDate"
                                        class="form-control datepicker">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-9">
                                        <button type="submit" id="newInvoiceSubmitBtn"
                                            class="btn btn-primary btn-flat btn-block">
                                            NEW INVOICE
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('back.invoices.index', $organization->id) }}"
                                            class="btn btn-danger btn-flat btn-block">
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

            $('#newInvoiceForm').submit(function(e) {
                e.preventDefault();
                $('#newInvoiceSubmitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#newInvoiceSubmitBtn').attr('disabled', true);
                var formData = new FormData(this);
                var path = '{{ route('back.invoices.store') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: formData,
                    dataType: "JSON",
                    success: function(data) {
                        $('#newInvoiceSubmitBtn').html('NEW INVOICE');
                        $('#newInvoiceSubmitBtn').attr('disabled', false);
                        if (data['status']) {
                            toastr.success(data.message);
                            setTimeout(function() {
                                window.location.href =
                                    '{{ route('back.invoices.view', $organization->id) }}';
                            }, 1000);
                        } else {
                            toastr.error(data['error']);
                        }
                    },
                    error: function(data) {
                        $('#newInvoiceSubmitBtn').html('NEW INVOICE');
                        $('#newInvoiceSubmitBtn').attr('disabled', false);
                        toastr.error('Something went wrong!');
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });

        });
    </script>
@endsection
