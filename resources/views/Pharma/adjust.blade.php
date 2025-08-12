<x-pharma-layout>
    <x-slot name="MainContent">
        <!-- Bootstrap & jQuery -->
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

            .suggestion-box {
                background-color: #161b22;
                border: 1px solid #30363d;
                color: #c9d1d9;
                position: absolute;
                z-index: 1000;
                width: 100%;
                max-height: 200px;
                overflow-y: auto;
                display: none;
            }

            .suggestion-box .list-group-item {
                background-color: #161b22;
                color: #c9d1d9;
                border: none;
            }

            .suggestion-box .list-group-item:hover {
                background-color: #21262d;
                color: #58a6ff;
            }

            .medicine-row {
                position: relative;
            }

            @media (max-width: 768px) {
                .medicine-row {
                    flex-direction: column;
                }

                .medicine-row .col-md-7,
                .medicine-row .col-md-3,
                .medicine-row .col-md-2 {
                    width: 100%;
                    margin-bottom: 1rem;
                }

                .remove-row {
                    margin-top: 0.5rem;
                }

                h2 {
                    font-size: 1.25rem;
                }

                .btn {
                    font-size: 0.95rem;
                }

                .text-center a.btn {
                    width: 100%;
                }
            }
        </style>

        <div class="container mt-5">
            <h2 class="mb-4 text-center">➕ Adjust Multiple Medicine Stock</h2>

            <div class="card shadow mb-5">
                <div class="card-body p-4">
                    <form method="post" action="{{ url('/inventory/update') }}">
                        @csrf
                        <input type="hidden" name="pharmacy_id" value="{{ session('Pharmacy_id') }}">

                        <div id="medicine-entries">
                            <div class="medicine-row mb-3 row">
                                <div class="col-md-7">
                                    <label class="form-label">Medicine Name</label>
                                    <input type="text" name="medicine_name[]" class="form-control medicine-name" placeholder="Enter medicine name..." autocomplete="off" required>
                                    <div class="suggestion-box list-group"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity[]" class="form-control" min="1" placeholder="Enter quantity..." required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-row w-100">Remove</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-info w-100 mb-3" id="add-medicine">+ Add Another Medicine</button>
                        <button type="submit" class="btn btn-success w-100 py-2">Submit All & Generate Bill</button>
                    </form>

                    @if(session('pdf_path'))
                        <div class="mt-4 text-center">
                            <a href="{{ session('pdf_path') }}" target="_blank" class="btn btn-primary px-4 py-2" style="font-weight: 600; font-size: 1.1rem;">
                                📄 View / Download Generated Bill PDF
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
            function bindAutocomplete(input) {
                input.on('keyup', function () {
                    let query = $(this).val();
                    let suggestionBox = $(this).siblings('.suggestion-box');

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
                            }
                        });
                    } else {
                        suggestionBox.hide();
                    }
                });

                input.siblings('.suggestion-box').on('click', 'a', function (e) {
                    e.preventDefault();
                    input.val($(this).text());
                    input.siblings('.suggestion-box').hide();
                });
            }

            $(document).ready(function () {
                bindAutocomplete($('.medicine-name').first());

                $('#add-medicine').click(function () {
                    let newRow = $('.medicine-row').first().clone();
                    newRow.find('input').val('');
                    newRow.find('.suggestion-box').html('').hide();
                    $('#medicine-entries').append(newRow);
                    bindAutocomplete(newRow.find('.medicine-name'));
                });

                $(document).on('click', '.remove-row', function () {
                    if ($('.medicine-row').length > 1) {
                        $(this).closest('.medicine-row').remove();
                    }
                });

                $(document).click(function (e) {
                    if (!$(e.target).closest('.medicine-name, .suggestion-box').length) {
                        $('.suggestion-box').hide();
                    }
                });
            });
        </script>
    </x-slot>
</x-pharma-layout>
