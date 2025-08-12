<x-pharma-layout>
    <x-slot name="MainContent">
        <style>
            body {
                background-color: #0f172a;
                color: #e2e8f0;
                font-family: 'Poppins', sans-serif;
            }

            .text-primary {
                color: #a3e635 !important;
                text-shadow: 0 0 6px #a3e635aa;
            }

            .btn-primary {
                background-color: #22d3ee;
                border: none;
                color: #0f172a;
                font-weight: 600;
            }

            .btn-primary:hover {
                background-color: #0ea5e9;
                color: #ffffff;
                box-shadow: 0 0 6px #22d3eeaa;
            }

            .btn-info {
                background-color: #38bdf8;
                border: none;
                color: #0f172a;
                font-weight: 600;
            }

            .btn-info:hover {
                background-color: #0ea5e9;
                color: #ffffff;
                box-shadow: 0 0 6px #22d3eeaa;
            }

            .table-dark th,
            .table-dark td {
                background-color: #1e293b;
                border-color: #334155;
            }

            .table-dark tr:hover {
                background-color: #334155;
            }

            .input-group input {
                background-color: #1e293b;
                color: #e2e8f0;
                border: 1px solid #334155;
            }

            .input-group input:focus {
                border-color: #22d3ee;
                box-shadow: 0 0 6px #22d3ee;
            }

            @media (max-width: 768px) {
                h3 {
                    font-size: 1.3rem;
                }

                .input-group {
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .input-group input,
                .input-group button {
                    width: 100%;
                }

                table {
                    font-size: 0.9rem;
                }
            }
        </style>

        <div class="container mt-4" style="max-width: 900px;">
            <h3 class="text-primary mb-3">📋 Customer History</h3>

            <!-- 🔍 Search Form -->
            <form method="GET" action="{{ url('/customer-history') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                           placeholder="Enter mobile number..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>

            <!-- 📋 Customer Table -->
            <table class="table table-striped table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Customer Mobile</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->customer_mobile }}</td>
                            <td>
                                <a href="{{ url('/customer-details/' . $customer->customer_mobile) }}"
                                   class="btn btn-sm btn-info">
                                    📄 View History
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No customer history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-slot>
</x-pharma-layout>
