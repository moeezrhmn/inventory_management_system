<!DOCTYPE html>
<html>

<head>
    <title>ADS Home Appliances Statements</title>
</head>

<body>
    <table border="1" style="width: 100%; " cellspacing="0" cellpadding="7">
        <thead>
            <tr>
                <th style="text-align: center;" colspan="4">Order & Quotation Statements (ADS Home Appliances)</th>
            </tr>
            <tr>
                <th colspan="4">{{ $customer->name }} ({{ $customer->phone }})</th>
            </tr>
            <tr>
                <th colspan="4" style="font-size: 25px;">Orders</th>
            </tr>
            <tr>
                <th>Date</th>
                <th> Order Items</th>
                <th>Credits</th>
                <th>Total</th>
            </tr>
        </thead>
        @php
            $grand_total = 0;
            $pending_total = 0;
            $paid_total = 0;
        @endphp
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ \Carbon\Carbon::parse($order['order_date'])->format('Y-m-d') }}</td>

                <!-- Nested table for Order Items -->
                <td  >
                    <table  cellspacing="0"  cellpadding="3" width="100%">
                        <thead>
                            <tr>
                                <th colspan="4">Items ({{ $order['invoice_no'] }})</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order['details'] as $item)
                            <tr>
                                <td>{{ $item['product']['name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ $item['unitcost'] }}</td>
                                <td>{{ $item['quantity'] * $item['unitcost'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>

                <!-- Nested table for Credits -->
                <td >
                    <table  cellspacing="0" cellpadding="3" width="100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Ref</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order['installments'] as $installment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($installment['created_at'])->format('Y-m-d') ?? 'N/A' }}</td>
                                <td>{{ $installment['payment'] ?? 'N/A' }}</td>
                                <td>{{ $installment['reference'] ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>

                <!-- Total column -->
                <td >
                    <table  cellspacing="0" cellpadding="3" width="100%">
                        @php
                            $grand_total += $order['total'];
                            $pending_total += $order['due'];
                            $paid_total += $order['pay'];
                        @endphp
                        <tbody>
                            <tr>
                                <th>Total amount</th>
                                <td>{{ $order['total'] }}</td>
                            </tr>
                            <tr>
                                <th>Total Paid </th>
                                <td>{{ $order['pay'] }}</td>
                            </tr>
                            <tr>
                                <th>Pending </th>
                                <td>{{ $order['due'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
            <tfoot>
                <tr>
                    <th colspan="2"> Grand Total </th>
                    <td colspan="2"> {{ number_format($grand_total, '2') }} </td>
                </tr>
                <tr>
                    <th colspan="2"> Total Pending</th>
                    <td colspan="2"> {{ number_format($pending_total, '2') }} </td>
                </tr>
                <tr>
                    <th colspan="2"> Total Paid</th>
                    <td colspan="2"> {{ number_format($paid_total, '2') }} </td>
                </tr>
            </tfoot>
        </tbody>
        <!-- <thead>
            <tr>
                <th colspan="4" style="font-size: 25px;">Quotations</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td> ... </td>
                <td> ... </td>
                <td> ... </td>
                <td> ... </td>
            </tr>
        </tbody> -->
    </table>

</body>

</html>