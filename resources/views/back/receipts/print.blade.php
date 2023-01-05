<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} | {{ $page_title }}</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome/css/font-awesome.min.css') }}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
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
            </div>
            <!--- Receipt End -->
        </div><!-- /.receipt-container -->
    </div><!-- ./wrapper -->
    <script type="text/javascript">
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
