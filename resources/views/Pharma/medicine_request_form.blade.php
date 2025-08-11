<x-pharma-layout>
    <x-slot name="MainContent">
        <div class="container mt-4" style="max-width: 1000px; background: #f0f4f8; padding: 20px; border-radius: 8px;">
            <h4 style="color: #222;">📝 Medicine Requests</h4>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="GET" action="{{ url('/medicine-request') }}" class="mb-3 d-flex" role="search">
                <input type="search" name="mobile" class="form-control me-2" placeholder="Search by Mobile Number"
                    value="{{ request('mobile') }}" aria-label="Search by mobile number" style="background: #fff; color: #222;">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="medicineRequestTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="prev-requests-tab" data-bs-toggle="tab"
                        data-bs-target="#prev-requests" type="button" role="tab" aria-controls="prev-requests"
                        aria-selected="true" style="color: #222;">Previous Requests</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="new-request-tab" data-bs-toggle="tab" data-bs-target="#new-request"
                        type="button" role="tab" aria-controls="new-request" aria-selected="false" style="color: #222;">New Request</button>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content border border-top-0 p-4" id="medicineRequestTabsContent" style="background: #e8edf3; color: #222; border-radius: 0 0 8px 8px;">
                <!-- Previous Requests Tab -->
                <div class="tab-pane fade show active" id="prev-requests" role="tabpanel"
                    aria-labelledby="prev-requests-tab">
                    @if ($requests->isEmpty())
                        <p>No medicine requests found.</p>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="table table-bordered table-hover" style="min-width: 900px; background: #fff;">
                                <thead class="table-light" style="background: #d0d9e6;">
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Mobile</th>
                                        <th>Medicine</th>
                                        <th>Notes</th>
                                        <th>Status</th>
                                        <th>Requested At</th>
                                        <th>Change Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests as $request)
                                        <tr>
                                            <td>{{ $request->customer_name }}</td>
                                            <td>{{ $request->customer_mobile }}</td>
                                            <td>{{ $request->medicine_name }}</td>
                                            <td>{{ $request->notes }}</td>
                                            <td><span
                                                    class="badge
                                                @if ($request->status == 'pending') bg-warning
                                                @elseif($request->status == 'fulfilled') bg-success
                                                @elseif($request->status == 'cancelled') bg-danger @endif
                                            ">{{ ucfirst($request->status) }}</span>
                                            </td>
                                            <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                                            <td style="min-width: 150px;">
                                                <form
                                                    action="{{ route('medicine-request.update-status', $request->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <select name="status" class="form-select form-select-sm"
                                                        onchange="this.form.submit()">
                                                        <option value="pending"
                                                            {{ $request->status == 'pending' ? 'selected' : '' }}>
                                                            Pending</option>
                                                        <option value="fulfilled"
                                                            {{ $request->status == 'fulfilled' ? 'selected' : '' }}>
                                                            Fulfilled</option>
                                                        <option value="cancelled"
                                                            {{ $request->status == 'cancelled' ? 'selected' : '' }}>
                                                            Cancelled</option>
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- New Request Tab -->
                <div class="tab-pane fade" id="new-request" role="tabpanel" aria-labelledby="new-request-tab">
                    <form method="POST" action="{{ url('/medicine-request') }}"
                        style="max-width: 700px; margin-top: 20px; background: #dce3ef; padding: 20px; border-radius: 8px; color: #222;">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control form-control-lg" required
                                style="background: #fff; color: #222;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mobile Number</label>
                            <input type="text" name="customer_mobile" class="form-control form-control-lg" required
                                style="background: #fff; color: #222;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Medicine Name</label>
                            <input type="text" name="medicine_name" class="form-control form-control-lg" required
                                style="background: #fff; color: #222;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" class="form-control form-control-lg" rows="4" style="background: #fff; color: #222;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>
</x-pharma-layout>
