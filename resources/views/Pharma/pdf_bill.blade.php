<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Medicine Bill</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2c3e50;
            margin: 40px;
            background: #fff;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #58a6ff;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-weight: 700;
            color: #0d3b66;
        }
        .pharmacy-details {
            text-align: left;
            font-size: 14px;
            line-height: 1.5;
            color: #444;
            margin-bottom: 30px;
        }
        .pharmacy-details p {
            margin: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
            box-shadow: 0 2px 5px rgb(0 0 0 / 0.1);
        }
        th, td {
            border: 1px solid #a0aec0;
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #0d3b66;
            color: #fff;
            font-weight: 600;
        }
        tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }
        tfoot td {
            font-weight: 700;
            background-color: #e2e8f0;
            font-size: 16px;
        }
        .total-row td {
            border-top: 3px solid #0d3b66;
        }
        .thank-you {
            font-size: 16px;
            text-align: center;
            color: #0d3b66;
            font-weight: 600;
            margin-top: 40px;
        }
        .thank-you span {
            display: block;
            font-weight: 400;
            font-size: 14px;
            margin-top: 5px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $pharmacy->pharmacy_name ?? 'Pharmacy' }} - Sales Invoice</h1>
        <div class="pharmacy-details">
            <p><strong>Address:</strong> {{ $pharmacy->address ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $pharmacy->phone ?? 'N/A' }}</p>
            <p><strong>Date:</strong> {{ $date instanceof \Carbon\Carbon ? $date->format('d M Y, h:i A') : $date }}</p>

            {{-- Customer Info --}}
            <p><strong>Customer Mobile:</strong> {{ $customer_mobile ?? 'N/A' }}</p>
            <p><strong>Customer Email:</strong> {{ $customer_email ?? 'N/A' }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Medicine Name</th>
                <th>Quantity</th>
                <th>Price (Rs)</th>
                <th>Amount (Rs)</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($summary as $item)
                <tr>
                    <td>{{ $item['medicine'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price'], 2) }}</td>
                    <td>{{ number_format($item['amount'], 2) }}</td>
                </tr>
                @php $total += $item['amount']; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Subtotal (Rs)</td>
                <td>{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total Discount (Rs)</td>
                <td>{{ number_format($total_discount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Grand Total (Rs)</td>
                <td>{{ number_format($grand_total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="thank-you">
        Thank you for trusting {{ $pharmacy->pharmacy_name ?? 'our pharmacy' }} with your health needs!
        <span>We wish you a speedy recovery and look forward to serving you again.</span>
    </div>
</body>
</html>
