<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Items Supplier's Report</title>
</head>

<body>


    <table border="1" style="width: 100%; " cellspacing="0" cellpadding="7">
        <thead>
            <tr>
                <th style="text-align: center;" colspan="6">Warehouse Items Statements (ADS Home Appliances)</th>
            </tr>
            <tr>
                <th colspan="6">{{ $supplier->name }} ({{ $supplier->phone }})</th>
            </tr>
            <tr>
                <th colspan="6" style="font-size: 25px;">Warehouse Items</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Per Unit Price</th>
                <th>Total Payment</th>
                <th>Total Paid</th>
            </tr>
        </thead>
        @php
            $grand_total_payment = 0;
            $grand_total_paid = 0;
            $grand_total_pending = 0;
        @endphp
        <tbody>
            @foreach($itemTransactions as $itemTransaction)
                @php
                    $grand_total_payment += (float) $itemTransaction['total_payment'];
                    $grand_total_paid += (float) $itemTransaction['total_paid'];
                    $grand_total_pending += (float) $itemTransaction['total_payment'] - (float) $itemTransaction['total_paid'];
                @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($itemTransaction['created_at'])->format('Y-m-d') }}</td>
                <td> {{ $itemTransaction['warehouse_item']['item_name']  }} </td>
                <td> {{ $itemTransaction['quantity'] .' ' . $itemTransaction['warehouse_item']['unit']['name'] }} </td>
                <td> {{ number_format($itemTransaction['per_piece_price']) }} </td>
                <td> {{ number_format($itemTransaction['total_payment']) }} </td>
                <td>
                    {{ number_format($itemTransaction['total_paid']) }}
                    <div>
                        <small> Pending: {{ number_format((float) $itemTransaction['total_payment'] - (float) $itemTransaction['total_paid'])  }} </small>
                    </div>
                </td>
            </tr>
            @endforeach
        <tfoot>
            <tr>
                <th colspan="3"> Grand Total </th>
                <td colspan="3"> {{ number_format($grand_total_payment, '2') }} </td>
            </tr>
            <tr>
                <th colspan="3"> Total Paid</th>
                <td colspan="3"> {{ number_format($grand_total_paid, '2') }} </td>
            </tr>
            <tr>
                <th colspan="3"> Total Pending</th>
                <td colspan="3"> {{ number_format($grand_total_pending, '2') }} </td>
            </tr>
        </tfoot>
        </tbody>

    </table>

</body>

</html>