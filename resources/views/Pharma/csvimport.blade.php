<x-pharma-layout>
    <x-slot name="MainContent">
        <div class="container">
            <h2 class="text-lime-neon mb-4 d-flex align-items-center justify-content-between">
                Import Inventory from CSV
                <a href="https://docs.google.com/spreadsheets/d/1ATMVHBoc_TLGpz0LbMd6JCOESTVFGG_lwLPr3RkKzZ8/edit?usp=drive_link" target="_blank" class="text-cyan-neon"
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

            <form action="{{ url('/pharmacy/import/csv') }}" method="POST" enctype="multipart/form-data"
                class="mb-5">
                @csrf
                <div class="mb-3">
                    <label for="csv_file" class="form-label">Select CSV File</label>
                    <input class="form-control" type="file" id="file" name="file"
                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        required />
                    <div class="form-text text-cyan-neon">CSV must contain: <strong>medicine_name, quantity,
                            price</strong></div>
                </div>

                <button type="submit" class="btn btn-cyan-neon"
                    style="background-color:#22d3ee; color:#0f172a; font-weight:600;">
                    Upload & Import
                </button>
            </form>
        </div>
    </x-slot>
</x-pharma-layout>
