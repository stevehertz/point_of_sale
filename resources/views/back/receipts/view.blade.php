@extends('back.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Receipt #{{ $receipt->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.dashboard.index', $organization->id) }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('back.receipts.index', $organization->id) }}">Receipts</a>
                        </li>
                        <li class="breadcrumb-item active">View Receipt</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div id="showScroll" class="receipt-container">
                        <div class="receipt">

                            <div class="logo">
                                <img src="{{ asset('storage/organizations') }}/{{ $organization->logo }}" alt="">
                            </div>

                            <div class="address">
                                {{ $organization->address }}
                                <P>
                                    PHONE: {{ $organization->phone }} EMAIL: {{ $organization->email }}
                                </P>
                            </div>

                            <div class="transactionDetails">
                                Served by: {{ $receipt->served_by }}
                            </div>

                            {{-- <p>1 ITEM</p> --}}
                            <table class="table">
                                <tbody>
                                    @forelse ($sale_products as $product)
                                        <tr>
                                            <td>{{ $product->product->product }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ $product->total_price }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No products</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="paymentDetails">
                                <div class="detail">DISCOUNT</div>
                                <div class="detail">{{ $receipt->discount }}</div>
                            </div>

                            <div class="paymentDetails">
                                <div class="detail">TAX( {{ $receipt->tax }} )</div>
                                <div class="detail">{{ $receipt->sale_tax }}</div>
                            </div>

                            <div class="paymentDetails">
                                <div class="detail">SHIPPING</div>
                                <div class="detail">{{ $receipt->shipping }}</div>
                            </div>

                            <div class="paymentDetails">
                                <div class="detail">TOTAL</div>
                                <div class="detail">{{ $receipt->paid_amount }}</div>
                            </div>

                            <div class="paymentDetails">
                                <div class="detail">CHARGE</div>
                                <div class="detail">{{ $receipt->change }}</div>
                            </div>
                            <br>
                            <div class="receiptBarcode">
                                <p>Payment Method: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $payment_method->method }}</p>
                                <p>{{ $receipt->payment_date }}</p>
                                <br />
                                <b5>
                                    <p> THANK YOU.</P>
                                </b5>
                            </div>
                            <hr>
                            <button data-id='{{ $receipt->id }}' data-token="{{ csrf_token() }}"
                                data-path="{{ route('back.receipts.show') }}"
                                class="btn btn-primary btn-block printReceiptBtn">
                                Print
                            </button>
                        </div>
                        <!--- Receipt End -->
                    </div>
                    <!--- Receipt Container End -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('load_scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click', '.printReceiptBtn', function(e) {
                e.preventDefault();
                var receipt_id = $(this).data('id');
                var token = $(this).data('token');
                var path = $(this).data('path');
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: {
                        _token: token,
                        receipt_id: receipt_id
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data['status'] == false) {
                            toastr.error(data['error']);
                        } else {
                            var pathURL =
                                '{{ route('back.receipts.print', $organization->id) }}';
                            window.open(pathURL, '_blank');
                        }
                    }
                });
            });

        });
    </script>
@endsection
