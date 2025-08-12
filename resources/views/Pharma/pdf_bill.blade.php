<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Medicine Bill</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2c3e50;
            margin: 20px;
            background: #fff;
            padding: 0 15px;
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
            font-size: 1.8rem;
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
            word-wrap: break-word;
        }
        /* Make table container horizontally scrollable on small screens */
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* smooth scrolling on iOS */
            box-shadow: 0 2px 5px rgb(0 0 0 / 0.1);
            margin-bottom: 35px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; /* ensures table layout on desktop */
        }
        th, td {
            border: 1px solid #a0aec0;
            padding: 12px 15px;
            text-align: left;
            vertical-align: middle;
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

        /* Responsive typography and spacing */
        @media (max-width: 768px) {
            body {
                margin: 15px 10px;
                padding: 0 10px;
            }
            .header h1 {
                font-size: 1.4rem;
            }
            .pharmacy-details {
                font-size: 13px;
                margin-bottom: 20px;
            }
            th, td {
                padding: 10px 8px;
                font-size: 13px;
            }
            tfoot td {
                font-size: 14px;
            }
            .thank-you {
                font-size: 14px;
                margin-top: 30px;
            }
            .thank-you span {
                font-size: 12px;
            }
            table {
                min-width: unset; /* allow table to shrink */
            }
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

    <div class="table-wrapper">
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
    </div>

    <div class="thank-you">
        Thank you for trusting {{ $pharmacy->pharmacy_name ?? 'our pharmacy' }} with your health needs!
        <span>We wish you a speedy recovery and look forward to serving you again.</span>
    </div>
</body>
</html>
