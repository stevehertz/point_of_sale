@extends('back.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Purchase</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('back/dashboard') }}/{{ $organization->id }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">New Purchase</li>
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
                        <form id="newPurchaseForm" role="form" autocomplete="off">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="newPurchaseOrganizationId" name="organization_id"
                                        value="{{ $organization->id }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="newPurchaseSupplier">Supplier</label>
                                    <select id="newPurchaseSupplierId" name="supplier_id" class="form-control select2" style="width: 100%;">
                                        <option selected="selected" disabled="disabled">Select Supplier</option>
                                        @forelse ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"> {{ $supplier->full_names }} </option>
                                        @empty
                                            <option disabled="disabled">No Suppliers</option>
                                        @endforelse
                                    </select>
                                    <small>
                                        <a href="{{ url('back/suppliers/'.$organization->id.'/create') }}">
                                            <i class="fa fa-plus-circle"></i> Add Supplier
                                        </a>
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="newPurchaseDate">Purchase Date</label>
                                    <input type="text" name="purchase_date" placeholder="Enter Purchase Date" id="newPurchaseDate" class="form-control datepicker">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-9">
                                        <button type="submit" id="newPurchaseSubmitBtn"
                                            class="btn btn-primary btn-flat btn-block">
                                            NEW PURCHASE
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('back.purchases.index', $organization->id) }}"
                                            class="btn btn-secondary btn-flat btn-block">
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

            $('#newPurchaseForm').submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                var path = '{{ route('back.purchases.store') }}';
                $.ajax({
                    url: path,
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $('#newPurchaseSubmitBtn').html(
                            '<i class="fa fa-spinner fa-spin"></i>'
                        );
                    },
                    complete:function(){
                        $('#newPurchaseSubmitBtn').html(
                            'NEW PURCHASE'
                        );
                    },
                    success: function(data) {
                        if (data['status']) {
                            toastr.success(data['message']);
                            window.location.href = '{{ route('back.purchases.view', $organization->id) }}';
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });

        });
    </script>
@endsection
