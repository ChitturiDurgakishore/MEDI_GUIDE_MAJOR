<x-pharma-layout>
    <x-slot name="MainContent">
        <!-- Bootstrap & jQuery -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <div class="container mt-5">
            <h2 class="mb-4 text-center">🧾 Pharmacy Inventory</h2>

            <!-- Search Form -->
            <form method="POST" action="{{ url('/InventorySearch') }}" class="row mb-4 position-relative">
                @csrf
                <div class="col-md-9 position-relative">
                    <input type="text" name="search" id="search-box" placeholder="Search medicine..."
                           value="{{ request('search') }}" autocomplete="off"
                           class="form-control rounded-md" />

                    <!-- Autocomplete Suggestions -->
                    <div id="suggestion-box"
                         class="list-group position-absolute w-100 rounded-md shadow"
                         style="z-index: 1000; max-height: 200px; overflow-y: auto; color:black; background-color:white; border: 1px solid #ddd;">
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 rounded-md shadow-sm">Search</button>
                </div>
            </form>

            <!-- Inventory Table -->
            <div class="card shadow rounded-lg">
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="py-3 px-4">Medicine Name</th>
                                <th class="py-3 px-4">Price (₹)</th>
                                <th class="py-3 px-4">Quantity</th>
                                <th class="py-3 px-4 text-center">Actions</th> {{-- New column for Adjust button --}}
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Check if $inventory is countable and has exactly one item --}}
                            @if(is_countable($inventory) && count($inventory) === 1)
                                {{-- If it's a collection with one item, iterate over it --}}
                                @foreach ($inventory as $inven)
                                <tr>
                                    <td class="py-2 px-4">{{ $inven->medicine_name }}</td>
                                    <td class="py-2 px-4">{{ $inven->price }}</td>
                                    <td class="py-2 px-4">{{ $inven->quantity }}</td>
                                    <td class="py-2 px-4 text-center">
                                        {{-- The 'Adjust' button links to an edit/update page for the specific medicine.
                                             You'll need to define the route '/inventory/adjust/{id}' in your web.php. --}}
                                        <a href="{{ url('/inventory/adjust/' . $inven->id) }}" class="btn btn-sm btn-info rounded-md"><i class="fas fa-pencil-alt"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            @elseif ($inventory && !is_countable($inventory) && isset($inventory->medicine_name))
                                {{-- Handle case where $inventory is a single model object (e.g., from ->first()) and is not countable but exists --}}
                                <tr>
                                    <td class="py-2 px-4">{{ $inventory->medicine_name }}</td>
                                    <td class="py-2 px-4">{{ $inventory->price }}</td>
                                    <td class="py-2 px-4">{{ $inventory->quantity }}</td>
                                    <td class="py-2 px-4 text-center">
                                        <a href="{{ url('/inventory/adjust/' . $inventory->id) }}" class="btn btn-sm btn-info rounded-md"><i class="fas fa-pencil-alt"></i></a>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No medicine found with that name.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            {{-- This pagination will only work if $inventory is a Paginator instance,
                 and we need to ensure $inventory is not null before calling method_exists --}}
            @if($inventory && method_exists($inventory, 'links'))
            <div class="mt-4 d-flex justify-content-center">
                {{ $inventory->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
            </div>
            @endif
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
                                        // Ensure the item.medicine_name is correctly accessed
                                        output += `<a href="#" class="list-group-item list-group-item-action">${item.medicine_name}</a>`;
                                    });
                                } else {
                                    output = '<div class="list-group-item text-muted">No results</div>';
                                }
                                $('#suggestion-box').html(output).show();
                            },
                            error: function(xhr, status, error) {
                                console.error("Autocomplete AJAX error:", status, error);
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
                    // Optionally, trigger the form submission when a suggestion is clicked
                    // $(this).closest('form').submit();
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
