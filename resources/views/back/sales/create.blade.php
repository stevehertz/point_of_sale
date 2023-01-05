@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Sale</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">New Sale</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card card-primary">
                        <div class="card-body">
                            <form id="newSaleForm" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" value="{{ $organization->id }}" name="organization_id"
                                        class="form-control" />
                                </div>

                                <div class="form-group">
                                    <label for="newSaleCustomerId">Customer</label>
                                    <select id="newSaleCustomerId" name="customer_id"
                                        class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger"
                                        style="width: 100%;">
                                        <option disabled="disabled" selected="selected">Choose Customer</option>
                                        <option value="0">Walk-in</option>
                                        @forelse ($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->full_names }}
                                            </option>
                                        @empty
                                            <option disabled="disabled">
                                                No Customer Found
                                            </option>
                                        @endforelse
                                    </select>
                                </div>
                                <!-- /.form-group -->
                                <small>
                                    <a href="{{ route('back.customers.create', $organization->id) }}">
                                        <i class="fa fa-plus-circle"></i> Add Customer
                                    </a>
                                </small>

                                <div class="form-group">
                                    <label for="newSaleDate">Sale Date</label>
                                    <input type="text" name="sales_date" class="form-control datepicker" id="newSaleDate"
                                        placeholder="Enter Sale Date">
                                </div>

                                <button type="submit" id="newSaleSubmitBtn" class="btn btn-block btn-success">Create
                                    Sale
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {
            $('#newSaleRow').fadeIn();
            $('#updateSaleRow').fadeOut();
            $('#newSaleForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var path = '{{ route('back.sales.store') }}';
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#newSaleSubmitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
                        $('#newSaleSubmitBtn').attr('disabled', true);
                    },
                    complete: function() {
                        $('#newSaleSubmitBtn').html('NEW SALE');
                        $('#newSaleSubmitBtn').attr('disabled', false);
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            setTimeout(function() {
                                window.location.href = '{{ route('back.sales.edit', $organization->id) }}';
                            }, 1000);
                        } else {
                            toastr.error(data['error']);
                        }
                    },
                    error: function(data) {
                        toastr.error(data.message);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        });
    </script>
@endsection
