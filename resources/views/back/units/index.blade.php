@extends('back.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Units List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Units</li>
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
                                <a href="#" class="btn btn-info newUnitBtn">
                                    <i class="fa fa-plus"></i> NEW UNIT
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-10">
                            <table id="unitsData" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Short Name</th>
                                        <th>Base Unit</th>
                                        <th>Operator</th>
                                        <th>Operation Value</th>
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

        <div class="modal fade" id="newUnitModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Unit</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="newUnitForm">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" name="organization_id" value="{{ $organization->id }}"
                                    class="form-control" id="newUnitOrganizationId" required>
                            </div>
                            <div class="form-group">
                                <label for="newUnitName">Name</label>
                                <input type="text" name="name" class="form-control" id="newUnitName"
                                    placeholder="Enter Name" required>
                            </div>
                            <div class="form-group">
                                <label for="newUnitShortName">Short Name</label>
                                <input type="text" name="short_name" class="form-control" id="newUnitShortName"
                                    placeholder="Enter Short Name" required>
                            </div>
                            <div class="form-group">
                                <label for="newUnitBaseUnit">Base Unit</label>
                                <select name="base_unit" id="newUnitBaseUnit" class="form-control select2"
                                    style="width: 100%;">
                                    <option disabled="disabled" selected="selected">Choose Name</option>
                                    @forelse ($units as $unit)
                                        <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                                    @empty
                                        <option value=''>No Unit</option>
                                    @endforelse
                                </select>
                            </div>
                            <div id="operatorSec" class="form-group">
                                <label for="newUnitOperator">Operator</label>
                                <select name="operator" id="newUnitOperator" class="form-control select2"
                                    style="width: 100%;">
                                    <option selected="selected" value="*">Multiply(*)</option>
                                    <option value="/">Divide(/)</option>
                                </select>
                            </div>
                            <div id="operationValueSec" class="form-group">
                                <label for="newUnitOperationValue">Operation Value</label>
                                <input type="text" name="operation_value" value="1" class="form-control"
                                    id="newUnitOperationValue" placeholder="Operation Value">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" id="newUnitSubmitBtn" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {

            find_units();

            function find_units() {
                var path = "{{ route('back.units.index', $organization->id) }}";
                $("#unitsData").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: path,
                    "autoWidth": false,
                    "responsive": true,
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'short_name',
                            name: 'short_name'
                        },
                        {
                            data: 'base_unit',
                            name: 'base_unit'
                        },
                        {
                            data: 'operator',
                            name: 'operator'
                        },
                        {
                            data: 'operation_value',
                            name: 'operation_value'
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

            $(document).on('click', '.newUnitBtn', function(e) {
                e.preventDefault();
                $('#newUnitModal').modal('show');
            });

            $('#newUnitForm').submit(function(e) {
                e.preventDefault();
                var form_data = new FormData(this);
                var path = "{{ route('back.units.store') }}";
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $('#newUnitSubmitBtn').prop('disabled', true);
                        $('#newUnitSubmitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    complete: function() {
                        $('#newUnitSubmitBtn').prop('disabled', false);
                        $('#newUnitSubmitBtn').html('Submit');
                    },
                    success: function(data) {
                        if (data['status']) {
                            $('#newUnitModal').modal('hide');
                            $('#newUnitForm')[0].reset();
                            $('#unitsData').DataTable().ajax.reload();
                            $('#operatorSec').fadeOut(900);
                            $('#operationValueSec').fadeOut(920);
                            location.reload();
                        } else {
                            $('#newUnitForm').find('.form-group').removeClass('has-error');
                            $('#newUnitForm').find('.form-group').find('.help-block').remove();
                            $.each(data['errors'], function(index, value) {
                                $('#newUnitForm').find('#' + index).closest(
                                    '.form-group').addClass('has-error');
                                $('#newUnitForm').find('#' + index).closest(
                                    '.form-group').append(
                                    '<span class="help-block">' + value + '</span>');
                            });
                            $('#operatorSec').fadeOut(900);
                            $('#operationValueSec').fadeOut(920);
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                toastr.error(value);
                            });
                        }
                    }
                });
            });

            $('#operatorSec').fadeOut(900);
            $('#operationValueSec').fadeOut(920);
            $('#newUnitBaseUnit').on('change', function() {
                var unit_name = $(this).val();
                if (unit_name == '') {
                    $('#operatorSec').fadeOut(900);
                    $('#operationValueSec').fadeOut(920);
                } else {
                    $('#operatorSec').fadeIn(800);
                    $('#operationValueSec').fadeIn(820);
                }
            });

            $(document).on('click', '.deleteProductUnit', function(e) {
                e.preventDefault();
                var unit_id = $(this).data('id');
                var path = '{{ route('back.units.delete') }}';
                var token = '{{ csrf_token() }}';
                swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this unit!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: path,
                            type: "POST",
                            data: {
                                unit_id: unit_id,
                                _token: token,
                            },
                            dataType: "json",
                            success: function(data) {
                                if (data['status']) {
                                    Swal.fire(data['message'], '', 'success')
                                    $('#unitsData').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(data['error'], '', 'error');
                                }
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info');
                    }
                })
            });
        });
    </script>
@endsection
