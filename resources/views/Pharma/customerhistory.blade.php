<x-pharma-layout>
    <x-slot name="MainContent">
        <div class="container mt-3" style="max-width: 900px;">
            <h3 class="text-primary mb-3">📋 Customer History</h3>

            <!-- 🔍 Search Form -->
            <form method="GET" action="{{ url('/customer-history') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Enter mobile number..." value="{{ request('search') }}">
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
                                <a href="{{ url('/customer-details/' . $customer->customer_mobile) }}" class="btn btn-sm btn-info">
                                    History
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
