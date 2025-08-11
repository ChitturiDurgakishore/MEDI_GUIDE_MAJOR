<!-- resources/views/Pharma/sales.blade.php -->
<x-pharma-layout>
    <x-slot name="MainContent">
        <div class="container-fluid">
            <h2 class="text-cyan-neon mb-4">Customer Purchase History</h2>

            <!-- Top Customer Card -->
            @if ($MainContent->isNotEmpty())
                <div class="card bg-dark border-info mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-lime-neon">
                                <i class="bi bi-person-fill"></i> Customer Details
                            </h5>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-info">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>

                        <p class="card-text h4 text-white">
                            Mobile: <span class="text-info">{{ $MainContent[0]->customer_mobile }}</span>
                        </p>
                        <p class="text-muted">Total Purchases: {{ $MainContent->count() }}</p>
                    </div>
                </div>

                <!-- Sales Table -->
                <div class="card bg-dark border-cyan">
                    <div class="card-header bg-dark text-lime-neon">
                        <h4><i class="bi bi-receipt"></i> Purchase History</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="bg-gray-800">
                                    <tr>
                                        <th class="text-cyan-neon">Id</th>
                                        <th class="text-cyan-neon">Medicine</th>
                                        <th class="text-cyan-neon">Qty</th>
                                        <th class="text-cyan-neon">Price</th>
                                        <th class="text-cyan-neon">Purchased At</th>
                                        <th class="text-cyan-neon">Stock Before</th>
                                        <th class="text-cyan-neon">Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($MainContent as $sale)
                                        <tr>
                                            <td>{{ $sale->id }}</td>
                                            <td>{{ $sale->medicine_name }}</td>
                                            <td>{{ $sale->quantity_sold }}</td>
                                            <td>₹{{ number_format($sale->price_at_sale, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('d M Y, H:i') }}</td>
                                            <td>{{ $sale->stock_before_sale }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $sale->day_of_week }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-dark text-muted">
                        Showing {{ $MainContent->count() }} records
                    </div>
                </div>

            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No purchase history found for this customer.
                </div>
            @endif
        </div>

        <style>
            .table-dark {
                background-color: #1e293b;
                color: #e2e8f0;
            }

            .table-dark th,
            .table-dark td {
                border-color: #334155;
            }

            .table-hover tbody tr:hover {
                background-color: #334155;
                color: #fff;
            }

            .card {
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            }

            .border-cyan {
                border: 1px solid #0ea5e9 !important;
            }

            .alert {
                background-color: #1e293b;
                border-color: #0ea5e9;
                color: #e2e8f0;
            }
        </style>
    </x-slot>
</x-pharma-layout>
