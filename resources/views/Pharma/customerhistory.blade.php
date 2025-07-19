<x-pharma-layout>
    <x-slot name="MainContent">
        <div class="container mt-3" style="max-width: 900px;">
            <h3 class="text-primary mb-3">📋 Customer History</h3>

            <table class="table table-striped table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Customer Mobile</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->customer_mobile }}</td>
                            <td>
                                <a href="{{ url('/customer-details/' . $customer->customer_mobile) }}"
                                    class="btn btn-sm btn-info">
                                    Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if ($customers->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center">No customer history found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-slot>
</x-pharma-layout>
