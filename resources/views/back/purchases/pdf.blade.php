<!DOCTYPE html>
<html>

<head>
    <title>Purchase</title>
</head>
<style type="text/css">
    body {
        font-family: 'Roboto Condensed', sans-serif;
    }

    .m-0 {
        margin: 0px;
    }

    .p-0 {
        padding: 0px;
    }

    .pt-5 {
        padding-top: 5px;
    }

    .mt-10 {
        margin-top: 10px;
    }

    .text-center {
        text-align: center !important;
    }

    .w-100 {
        width: 100%;
    }

    .w-50 {
        width: 50%;
    }

    .w-85 {
        width: 85%;
    }

    .w-15 {
        width: 15%;
    }

    .logo img {
        width: 45px;
        height: 45px;
        padding-top: 30px;
    }

    .logo span {
        margin-left: 8px;
        top: 19px;
        position: absolute;
        font-weight: bold;
        font-size: 25px;
    }

    .gray-color {
        color: #5D5D5D;
    }

    .text-bold {
        font-weight: bold;
    }

    .border {
        border: 1px solid black;
    }

    table tr,
    th,
    td {
        border: 1px solid #d2d2d2;
        border-collapse: collapse;
        padding: 7px 8px;
    }

    table tr th {
        background: #F4F4F4;
        font-size: 15px;
    }

    table tr td {
        font-size: 13px;
    }

    table {
        border-collapse: collapse;
    }

    .box-text p {
        line-height: 10px;
    }

    .float-left {
        float: left;
    }

    .total-part {
        font-size: 16px;
        line-height: 12px;
    }

    .total-right p {
        padding-right: 20px;
    }

</style>

<body>
    <div class="head-title">
        <h1 class="text-center m-0 p-0">
            Purchase
        </h1>
    </div>
    <div class="add-detail mt-10">
        <div class="w-50 float-left mt-10">
            <p class="m-0 pt-5 text-bold w-100">Purchase Id - <span class="gray-color">#{{ $purchase->id }}</span>
            </p>
            <p class="m-0 pt-5 text-bold w-100">Purchase Date - <span
                    class="gray-color">{{ $purchase->purchase_date }}</span></p>
        </div>
        <div class="w-50 float-left logo mt-10">
            {{-- <img src="https://www.nicesnippets.com/image/imgpsh_fullsize.png"> <span>Nicesnippets.com</span> --}}
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">From</th>
                <th class="w-50">To</th>
            </tr>
            <tr>
                <td>
                    <div class="box-text">
                        <p>{{ $organization->organization }}</p>
                        <p>{{ $organization->address }}</p>
                        <p>Phone Number : {{ $organization->phone }}</p>
                        <p>Email Address: {{ $organization->email }}</p>
                    </div>
                </td>
                <td>
                    <div class="box-text">
                        <p>{{ $supplier->full_names }}</p>
                        <p>{{ $supplier->address }}</p>
                        <p>Phone Number : {{ $supplier->phone }}</p>
                        <p>Email Address: {{ $supplier->email }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">Payment Method</th>
                <th class="w-50">Shipping Method</th>
            </tr>
            <tr>
                <td>Cash On Delivery</td>
                <td>Free Shipping - Free Shipping</td>
            </tr>
        </table>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">Product Name</th>
                <th class="w-50">Price</th>
                <th class="w-50">Qty</th>
                <th class="w-50">Subtotal</th>
            </tr>
            @forelse ($purchase_products as $product)
                <tr align="center">
                    <td>{{ $product->product->product }}</td>
                    <td>{{ $product->product->purchase_price }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->total_amount }}</td>
                </tr>
            @empty
            @endforelse
            <tr>
                <td colspan="7">
                    <div class="total-part">
                        <div class="total-left w-85 float-left" align="right">
                            <p>Order Total: </p>
                            <p>Discount: </p>
                            <p>Subtotal: </p>
                            <p>Prev Balance: </p>
                            <p>Total: </p>
                            <p>Paid Amount: </p>
                            <p>Balance: </p>
                        </div>
                        <div class="total-right w-15 float-left text-bold" align="right">
                            <p>{{ number_format($purchase->order_amount, 2, '.', ',') }}</p>
                            <p>{{ number_format($purchase->discount, 2, '.', ',') }}</p>
                            <p>
                                {{ number_format($purchase->subtotal, 2, '.', ',') }}
                            </p>
                            <p>
                                {{ number_format($purchase->prev_balance, 2, '.', ',') }}
                            </p>
                            <p>
                                {{ number_format($purchase->total, 2, '.', ',') }}
                            </p>
                            <p>
                                {{ number_format($purchase->paid_amount, 2, '.', ',') }}
                            </p>
                            <p>
                                {{ number_format($purchase->balance, 2, '.', ',') }}
                            </p>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</html>
