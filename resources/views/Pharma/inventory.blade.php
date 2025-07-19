<x-pharma-layout>
    <x-slot name="MainContent">
        <!-- Bootstrap & jQuery -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <div class="container mt-5">
            <h2 class="mb-4 text-center">🧾 Pharmacy Inventory</h2>

            <!-- Search Form -->
            <form method="POST" action="{{ url('/InventorySearch') }}" class="row mb-4 position-relative">
                @csrf
                <div class="col-md-9 position-relative">
                    <input type="text" name="search" id="search-box" placeholder="Search medicine..."
                           value="{{ request('search') }}" autocomplete="off"
                           class="form-control" />

                    <!-- Autocomplete Suggestions -->
                    <div id="suggestion-box"
                         class="list-group position-absolute w-100"
                         style="z-index: 1000; max-height: 200px; overflow-y: auto; color:black; background-color:white">
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>

            <!-- Inventory Table -->
            <div class="card shadow">
                <div class="card-body p-0">
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
                                    <td>{{ $inven->price }}</td>
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

                // Hide on outside click
                $(document).click(function (e) {
                    if (!$(e.target).closest('#search-box, #suggestion-box').length) {
                        $('#suggestion-box').hide();
                    }
                });
            });
        </script>
    </x-slot>
</x-pharma-layout>
