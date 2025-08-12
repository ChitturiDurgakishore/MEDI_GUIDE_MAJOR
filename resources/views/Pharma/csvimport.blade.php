<x-pharma-layout>
    <x-slot name="MainContent">
        <style>
            body {
                background-color: #0f172a;
                color: #e2e8f0;
                font-family: 'Poppins', sans-serif;
            }

            h2 {
                color: #a3e635;
                text-shadow: 0 0 5px #a3e635aa;
            }

            .text-lime-neon {
                color: #a3e635;
                text-shadow: 0 0 8px #a3e635aa;
            }

            .text-cyan-neon {
                color: #22d3ee;
                text-shadow: 0 0 6px #22d3eeaa;
            }

            .btn-cyan-neon {
                background-color: #22d3ee;
                color: #0f172a;
                font-weight: 600;
                border: none;
                transition: all 0.3s ease-in-out;
            }

            .btn-cyan-neon:hover {
                background-color: #0ea5e9;
                color: #ffffff;
                box-shadow: 0 0 10px #22d3eeaa;
            }

            .form-control {
                background-color: #1e293b;
                color: #e2e8f0;
                border: 1px solid #334155;
            }

            .form-control:focus {
                background-color: #1e293b;
                color: #ffffff;
                border-color: #22d3ee;
                box-shadow: 0 0 6px #22d3ee;
            }

            .form-text {
                font-size: 0.875rem;
            }

            .alert {
                font-size: 0.95rem;
                border-radius: 6px;
            }

            @media (max-width: 768px) {
                h2 {
                    font-size: 1.25rem;
                    flex-direction: column;
                    align-items: flex-start !important;
                    gap: 0.5rem;
                }

                .btn-cyan-neon {
                    width: 100%;
                }
            }
        </style>

        <div class="container mt-5">
            <h2 class="text-lime-neon mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                Import Inventory from CSV
                <a href="https://docs.google.com/spreadsheets/d/1ATMVHBoc_TLGpz0LbMd6JCOESTVFGG_lwLPr3RkKzZ8/edit?usp=drive_link"
                   target="_blank" class="text-cyan-neon"
                   style="font-weight: 600; font-size: 1rem; text-decoration: underline;">
                    Want Excel sheet?
                </a>
            </h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/pharmacy/import/csv') }}" method="POST" enctype="multipart/form-data" class="mb-5">
                @csrf

                <div class="mb-3">
                    <label for="file" class="form-label">Select CSV File</label>
                    <input class="form-control" type="file" id="file" name="file"
                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        required />
                    <div class="form-text text-cyan-neon">
                        CSV must contain: <strong>medicine_name, quantity, price</strong>
                    </div>
                </div>

                <button type="submit" class="btn btn-cyan-neon px-4 py-2">
                    📤 Upload & Import
                </button>
            </form>
        </div>
    </x-slot>
</x-pharma-layout>
