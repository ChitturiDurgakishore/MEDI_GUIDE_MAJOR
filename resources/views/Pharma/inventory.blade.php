<x-pharma-layout>
    <x-slot name="MainContent">

        <!-- Responsive Meta Tag -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap & jQuery -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <style>
            /* Ensure suggestion box appears above all */
            #suggestion-box {
                z-index: 1050; /* Higher than Bootstrap modals */
                max-height: 200px;
                overflow-y: auto;
                background-color: white;
                color: black;
                border: 1px solid #ced4da;
                border-radius: 0 0 0.375rem 0.375rem;
            }

            /* Make table scroll horizontally on small devices */
            .table-responsive {
                overflow-x: auto;
            }
        </style>

        <div class="container mt-5">

            <h2 class="mb-4 text-center">🧾 Pharmacy Inventory</h2>

            <!-- Search Form -->
            <form method="POST" action="{{ url('/InventorySearch') }}" class="row mb-4 position-relative" autocomplete="off">
                @csrf
                <div class="col-12 col-md-9 position-relative mb-2 mb-md-0">
                    <input type="text" name="search" id="search-box" placeholder="Search medicine..."
                        value="{{ request('search') }}" class="form-control" />

                    <!-- Autocomplete Suggestions -->
                    <div id="suggestion-box" class="list-group position-absolute w-100" style="display:none;"></div>
                </div>

                <div class="col-12 col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>

            <!-- Inventory Table Responsive Wrapper -->
            <div class="card shadow">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Medicine Name</th>
                                <th>Price (₹)</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inventory as $inven)
                                <tr>
                                    <td>{{ $inven->medicine_name }}</td>
                                    <td>{{ number_format($inven->price, 2) }}</td>
                                    <td>{{ $inven->quantity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No medicines found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $inventory->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- AJAX Autocomplete Script -->
        <script>
            $(document).ready(function () {
                $('#search-box').on('keyup', function () {
                    let query = $(this).val();

                    if (query.length > 0) {
                        $.ajax({
                            url: "{{ url('/autocomplete') }}",
                            method: "GET",
                            data: { query: query },
                            success: function (data) {
                                let output = '';
                                if (data.length > 0) {
                                    data.forEach(function (item) {
                                        output += `<a href="#" class="list-group-item list-group-item-action">${item.medicine_name}</a>`;
                                    });
                                } else {
                                    output = '<div class="list-group-item text-muted">No results</div>';
                                }
                                $('#suggestion-box').html(output).show();
                            },
                            error: function () {
                                $('#suggestion-box').html('<div class="list-group-item text-danger">Error loading suggestions.</div>').show();
                            }
                        });
                    } else {
                        $('#suggestion-box').hide();
                    }
                });

                // Fill input when suggestion clicked
                $(document).on('click', '#suggestion-box a', function (e) {
                    e.preventDefault();
                    $('#search-box').val($(this).text());
                    $('#suggestion-box').hide();
                });

                // Hide suggestions on outside click
                $(document).click(function (e) {
                    if (!$(e.target).closest('#search-box, #suggestion-box').length) {
                        $('#suggestion-box').hide();
                    }
                });
            });
        </script>
    </x-slot>
</x-pharma-layout>
