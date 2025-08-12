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
            <form method="POST" action="{{ url('/InventorySearch') }}" class="row mb-4 position-relative gx-2 gx-md-3">
                @csrf
                <div class="col-12 col-md-9 position-relative mb-2 mb-md-0">
                    <input type="text" name="search" id="search-box" placeholder="Search medicine..."
                           value="{{ request('search') }}" autocomplete="off"
                           class="form-control rounded-md" />

                    <!-- Autocomplete Suggestions -->
                    <div id="suggestion-box"
                         class="list-group position-absolute w-100 rounded-md shadow"
                         style="z-index: 1000; max-height: 200px; overflow-y: auto; color:black; background-color:white; border: 1px solid #ddd;">
                    </div>
                </div>

                <div class="col-12 col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary rounded-md shadow-sm w-100">Search</button>
                </div>
            </form>

            <!-- Inventory Table -->
            <div class="card shadow rounded-lg overflow-auto">
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 table-responsive">
                        <thead class="table-dark">
                            <tr>
                                <th class="py-3 px-3">Medicine Name</th>
                                <th class="py-3 px-3">Price (₹)</th>
                                <th class="py-3 px-3">Quantity</th>
                                <th class="py-3 px-3 text-center">Actions</th> {{-- Adjust button --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if(is_countable($inventory) && count($inventory) === 1)
                                @foreach ($inventory as $inven)
                                <tr>
                                    <td class="py-2 px-3">{{ $inven->medicine_name }}</td>
                                    <td class="py-2 px-3">{{ $inven->price }}</td>
                                    <td class="py-2 px-3">{{ $inven->quantity }}</td>
                                    <td class="py-2 px-3 text-center">
                                        <a href="{{ url('/inventory/adjust/' . $inven->id) }}" class="btn btn-sm btn-info rounded-md" title="Adjust stock">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @elseif ($inventory && !is_countable($inventory) && isset($inventory->medicine_name))
                                <tr>
                                    <td class="py-2 px-3">{{ $inventory->medicine_name }}</td>
                                    <td class="py-2 px-3">{{ $inventory->price }}</td>
                                    <td class="py-2 px-3">{{ $inventory->quantity }}</td>
                                    <td class="py-2 px-3 text-center">
                                        <a href="{{ url('/inventory/adjust/' . $inventory->id) }}" class="btn btn-sm btn-info rounded-md" title="Adjust stock">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
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
                });

                // Hide on outside click
                $(document).click(function (e) {
                    if (!$(e.target).closest('#search-box, #suggestion-box').length) {
                        $('#suggestion-box').hide();
                    }
                });
            });
        </script>

        <style>
            /* Make the suggestion box full width on mobile */
            @media (max-width: 767.98px) {
                #suggestion-box {
                    max-width: 100vw !important;
                    left: 0 !important;
                    right: 0 !important;
                    border-radius: 0 !important;
                }
            }

            /* Smaller padding for table cells on mobile */
            @media (max-width: 575.98px) {
                table.table td,
                table.table th {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.9rem;
                }

                /* Make the "Actions" column buttons smaller */
                .btn-sm {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.75rem;
                }
            }
        </style>
    </x-slot>
</x-pharma-layout>
