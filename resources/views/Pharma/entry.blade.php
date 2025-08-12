<x-pharma-layout>
    <x-slot name="MainContent">

        <!-- Responsive Meta Tag -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <style>
            body {
                background-color: #0d1117;
                color: #c9d1d9;
            }

            h2 {
                color: #58a6ff;
                text-shadow: 0 0 5px #58a6ff;
            }

            .form-label {
                color: #58a6ff;
                font-weight: 500;
            }

            .form-control {
                background-color: #0d1117;
                color: #c9d1d9;
                border: 1px solid #30363d;
            }

            .form-control::placeholder {
                color: #8b949e;
            }

            .form-control:focus {
                background-color: #0d1117;
                color: #ffffff;
                border-color: #58a6ff;
                box-shadow: 0 0 5px #58a6ff;
            }

            .btn-success {
                background-color: #238636;
                border-color: #2ea043;
            }

            .btn-success:hover {
                background-color: #2ea043;
                border-color: #238636;
                box-shadow: 0 0 8px #2ea043;
            }

            .card {
                background-color: #161b22;
                border: 1px solid #30363d;
            }

            #suggestion-box {
                background-color: #161b22;
                border: 1px solid #30363d;
                color: #c9d1d9;
            }

            #suggestion-box .list-group-item {
                background-color: #161b22;
                color: #c9d1d9;
                border: none;
            }

            #suggestion-box .list-group-item:hover {
                background-color: #21262d;
                color: #58a6ff;
            }

            @media (max-width: 768px) {
                h2 {
                    font-size: 1.4rem;
                    text-align: center;
                }

                .form-label {
                    font-size: 0.9rem;
                }

                .btn {
                    font-size: 0.9rem;
                }
            }
        </style>

        <div class="container mt-4 mb-5">
            <h2 class="mb-4 text-center">➕ Add New Medicine Entry</h2>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! session('success') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="{{ url('/inventory/add-medicine') }}">
                        @csrf
                        <input type="hidden" name="pharmacy_id" value="{{ session('Pharmacy_id') }}">

                        <div class="mb-3 position-relative">
                            <label for="medicine-name" class="form-label">Medicine Name</label>
                            <input type="text" name="medicine_name" id="medicine-name" class="form-control" required autocomplete="off" placeholder="Enter medicine name...">
                            <div id="suggestion-box" class="list-group position-absolute w-100"
                                 style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></div>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" required min="1" placeholder="Enter quantity...">
                        </div>

                        <div class="mb-4">
                            <label for="price" class="form-label">Price (₹)</label>
                            <input type="number" name="price" id="price" class="form-control" required step="0.01" min="0" placeholder="Enter price...">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Add Medicine</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 🔄 AJAX Autocomplete -->
        <script>
            $(document).ready(function () {
                $('#medicine-name').on('keyup', function () {
                    let query = $(this).val();
                    let suggestionBox = $('#suggestion-box');

                    if (query.length > 0) {
                        $.ajax({
                            url: "{{ url('/autocompletewhole') }}",
                            method: "GET",
                            data: { query: query },
                            success: function (data) {
                                let output = '';
                                if (data.length > 0) {
                                    data.forEach(function (item) {
                                        output += `<a href="#" class="list-group-item list-group-item-action">${item.medicinename}</a>`;
                                    });
                                } else {
                                    output = '<div class="list-group-item text-muted">No results</div>';
                                }
                                suggestionBox.html(output).show();
                            },
                            error: function () {
                                suggestionBox.html('<div class="list-group-item text-danger">Error loading suggestions.</div>').show();
                            }
                        });
                    } else {
                        suggestionBox.hide();
                    }
                });

                $(document).on('click', '#suggestion-box a', function (e) {
                    e.preventDefault();
                    $('#medicine-name').val($(this).text());
                    $('#suggestion-box').hide();
                });

                $(document).click(function (e) {
                    if (!$(e.target).closest('#medicine-name, #suggestion-box').length) {
                        $('#suggestion-box').hide();
                    }
                });
            });
        </script>
    </x-slot>
</x-pharma-layout>
