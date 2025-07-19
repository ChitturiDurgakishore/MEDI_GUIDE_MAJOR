<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Medi-Guide Search</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;700&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        html {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #1a202c;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 2rem 1rem;
            box-sizing: border-box;
            color: #d1d5db;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
            line-height: 1.3;
        }

        .text-lime-neon {
            color: #a3e635;
        }

        .text-cyan-neon {
            color: #22d3ee;
        }

        .bg-cyan-neon {
            background-color: #06b6d4;
        }

        .border-cyan-neon {
            border-color: #0e7490 !important;
        }

        .bg-dark-card {
            background-color: #2d3748;
        }

        .btn-neon {
            transition: all 0.3s ease-in-out;
            font-weight: 600;
            border-radius: 999px;
            border: none;
        }

        .btn-neon:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 255, 255, 0.2);
        }

        .form-control-dark {
            background-color: #1a202c;
            color: #d1d5db;
            border-color: #0e7490;
        }

        .form-control-dark:focus {
            background-color: #1a202c;
            color: #d1d5db;
            border-color: #06b6d4;
            box-shadow: 0 0 0 0.25rem rgba(6, 182, 212, 0.25);
        }

        .table-custom {
            border-radius: 1rem;
            overflow: hidden;
        }

        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            backdrop-filter: blur(4px);
        }

        #loading-overlay h4 {
            color: #a3e635;
            margin-top: 1rem;
            font-family: 'Poppins', sans-serif;
            text-shadow: 0 0 8px #a3e635;
        }

        .pharmacy-loader-container {
            position: relative;
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pharmacy-symbol {
            width: 60px;
            height: 60px;
            fill: #22d3ee;
            z-index: 10;
        }

        .pharmacy-circle-loader {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 6px solid rgba(163, 230, 53, 0.2);
            border-top: 6px solid #a3e635;
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Autocomplete Suggestions Styling */
        .autocomplete-suggestions {
            position: absolute;
            z-index: 1000;
            width: 100%;
            /* Take full width of parent col-md-8 */
            top: calc(100% + 5px);
            /* Position slightly below the input */
            background: #2d3748;
            /* Dark card background */
            border: 1px solid #0e7490;
            /* Border similar to other elements */
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            display: none;
            /* Hidden by default */
            max-height: 200px;
            /* Limit height */
            overflow-y: auto;
            /* Enable scrolling */
            color: #d1d5db;
            /* Text color */
        }

        .autocomplete-suggestion-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid rgba(14, 116, 144, 0.3);
            /* Lighter border for items */
            text-align: left;
            /* Align text left */
        }

        .autocomplete-suggestion-item:last-child {
            border-bottom: none;
        }

        .autocomplete-suggestion-item:hover {
            background-color: #06b6d4;
            /* Cyan neon for hover */
            color: #fff;
            /* White text on hover */
        }

        /* Styles for the new header */
        .navbar-custom {
            background-color: #2d3748; /* Darker background, similar to card */
            border-bottom: 1px solid #0e7490; /* Cyan neon border */
            padding: 1rem 0;
            margin-bottom: 2rem; /* Space below the navbar */
        }

        .navbar-brand-custom {
            color: #a3e635 !important; /* Lime neon for brand */
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
        }

        .navbar-brand-custom:hover {
            color: #a3e635 !important; /* Keep color on hover */
        }

        .nav-link-custom {
            color: #d1d5db !important; /* Light text for links */
            font-weight: 600;
            margin-left: 1rem;
            transition: color 0.3s ease-in-out;
        }

        .nav-link-custom:hover {
            color: #22d3ee !important; /* Cyan neon on hover */
        }

        .navbar-toggler-custom {
            border-color: rgba(163, 230, 53, 0.5) !important;
        }

        .navbar-toggler-icon-custom {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28163, 230, 53, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
        <div class="container-fluid px-4">
            <a class="navbar-brand navbar-brand-custom" href="/">
                <svg class="me-2" viewBox="0 0 24 24" fill="none" width="30" height="30"
                    xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="2" width="20" height="20" rx="4" fill="#FF0000" />
                    <path d="M11 5H13V11H19V13H13V19H11V13H5V11H11V5Z" fill="#FFFFFF" />
                </svg>
                Medi-Guide
            </a>
            <button class="navbar-toggler navbar-toggler-custom" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon navbar-toggler-icon-custom"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="/chatbot">Chatbot</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="loading-overlay">
        <div class="pharmacy-loader-container">
            <svg class="pharmacy-symbol" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm3 11h-2v2h-2v-2H9v-2h2V9h2v2h2v2z" />
            </svg>
            <div class="pharmacy-circle-loader"></div>
        </div>
        <h4>Searching medicine...</h4>
    </div>

    <div class="container-fluid px-3" style="margin-top: 6rem;"> <div class="bg-dark-card p-4 p-md-5 rounded-4 card-shadow mx-auto text-center border border-cyan-neon"
            style="max-width: 960px;">

            <header class="mb-5">
                <h1
                    class="display-5 mb-3 text-lime-neon d-flex align-items-center justify-content-center flex-wrap text-center">
                    <svg class="me-2 mb-2 mb-md-0" viewBox="0 0 24 24" fill="none" width="36" height="36"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="2" width="20" height="20" rx="4" fill="#FF0000" />
                        <path d="M11 5H13V11H19V13H13V19H11V13H5V11H11V5Z" fill="#FFFFFF" />
                    </svg>
                    Medi-Guide
                </h1>
            </header>

            <section class="mb-5">
                <h2 class="fs-3 text-lime-neon mb-4 text-center">Search for Medicine</h2>
                <form action="{{ url('/Result') }}" method="POST" class="mx-auto row gx-2 gy-2 justify-content-center"
                    style="max-width: 600px;">
                    @csrf
                    <div class="col-12 col-md-8 position-relative">
                        <input type="text"
                            class="form-control form-control-lg form-control-dark rounded-pill"
                            placeholder="Enter medicine name..." name="MedicineName" id="MedicineNameInput"
                            autocomplete="off" value="{{ old('MedicineName') }}" required>
                        <div id="suggestions" class="autocomplete-suggestions"></div>
                    </div>
                    <div class="col-12 col-md-4 d-grid">
                        <button class="btn btn-lg bg-cyan-neon text-white btn-neon rounded-pill"
                            type="submit">Search</button>
                    </div>
                </form>
            </section>

            {{-- Conditional display for search results --}}
            @if (isset($searchedMedicine) || request('MedicineName'))
                <section class="text-start mb-5">
                    <h3 class="text-cyan-neon mb-3">Searched Medicine:</h3>
                    <div class="table-responsive">
                        <table class="table table-dark table-bordered table-custom">
                            <thead>
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Price (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $searchedMedicine->medicinename ?? request('MedicineName') }}</td>
                                    <td>{{ $searchedMedicine->price ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="text-start mb-5">
                    <h3 class="text-cyan-neon mb-3">Available in Stores:</h3>
                    @if (isset($details) && count($details))
                        <div class="table-responsive">
                            <table class="table table-dark table-bordered table-hover table-custom">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pharmacy</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Distance</th>
                                        <th>Map</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $index => $pharmacy)
                                        <tr>
                                            <td>{{ $index + 1 + ($details->currentPage() - 1) * $details->perPage() }}</td>
                                            <td>{{ $pharmacy->pharmacy_name }}</td>
                                            <td>{{ $pharmacy->phone }}</td>
                                            <td>{{ $pharmacy->address }}</td>
                                            <td>{{ isset($pharmacy->distance) ? round($pharmacy->distance, 2) . ' km' : 'N/A' }}
                                            </td>
                                            <td><a href="/Pharmacy/Details/{{ $pharmacy->id }}/{{ $searchedMedicine->medicinename ?? request('MedicineName') }}"
                                                    class="btn btn-sm btn-outline-success">Details</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Add pagination links here --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $details->appends(request()->input())->links() }}
                        </div>
                    @else
                        <div class="alert alert-warning">No stores found nearby with this medicine.</div>
                    @endif
                </section>

                @if (isset($prices) && count($prices))
                    <section class="text-start mb-5">
                        <h3 class="text-cyan-neon mb-3">Alternatives [Low to High]:</h3>
                        <div class="table-responsive">
                            <table class="table table-dark table-bordered table-hover table-custom">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Medicine</th>
                                        <th>Price (₹)</th>
                                        <th>Search</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prices as $index => $price)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $price->medicinename }}</td>
                                            <td>{{ $price->price }}</td>
                                            <td>
                                                <form action="{{ url('/Result') }}" method="POST" class="m-0 p-0">
                                                    @csrf
                                                    <input type="hidden" name="MedicineName"
                                                        value="{{ $price->medicinename }}">
                                                    <button class="btn btn-sm btn-outline-info">View</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif

                <section class="text-start mb-5">
                    <h3 class="text-cyan-neon mb-3">Medicine Image:</h3>
                    <script async src="https://cse.google.com/cse.js?cx=c32e083e554bd415e"></script>
                    <div class="gcse-search" data-queryParameterName="q" data-enableAutoComplete="true"
                        data-searchtype="image"></div>
                    <script>
                        window.addEventListener('load', () => {
                            const q = "{{ $searchedMedicine->medicinename ?? request('MedicineName') }}";
                            const interval = setInterval(() => {
                                const input = document.querySelector('input.gsc-input');
                                if (input) {
                                    input.value = q;
                                    const e = new KeyboardEvent('keydown', {
                                        bubbles: true,
                                        cancelable: true,
                                        keyCode: 13
                                    });
                                    input.dispatchEvent(e);
                                    clearInterval(interval);
                                }
                            }, 100);
                        });
                    </script>
                </section>
            @endif {{-- End of conditional display for search results --}}

            <footer class="mt-5 pt-3 border-top border-secondary text-sm text-secondary">
                <p class="mb-2">&copy; 2025 Medi-Guide. All rights reserved. &copy; KISHORE</p>
            </footer>

        </div>
    </div>

    <script>
        window.addEventListener("load", function() {
            const overlay = document.getElementById("loading-overlay");
            // Only hide overlay if it was shown by a previous submission
            // On initial load, it should be hidden by default from CSS
            if (overlay && overlay.style.display === "flex") {
                overlay.style.display = "none";
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const form = document.querySelector("form[action='{{ url('/Result') }}']");
                    if (form) {
                        form.insertAdjacentHTML("beforeend", `
                            <input type="hidden" name="user_latitude" value="${lat}">
                            <input type="hidden" name="user_longitude" value="${lon}">
                        `);
                    }
                }, function(error) {
                    console.warn('Geolocation access denied or not available:', error);
                    // Optionally, inform the user or suggest manual location input
                });
            }
        });

        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", () => {
                const overlay = document.getElementById("loading-overlay");
                if (overlay) overlay.style.display = "flex";
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('MedicineNameInput');
            const suggestionsBox = document.getElementById('suggestions');
            let debounceTimer;

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                if (query.length < 2) { // Start suggesting after 2 characters
                    suggestionsBox.style.display = 'none';
                    suggestionsBox.innerHTML = '';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetchSuggestions(query);
                }, 300); // Debounce time set to 300ms
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', (e) => {
                if (!suggestionsBox.contains(e.target) && e.target !== input) {
                    suggestionsBox.style.display = 'none';
                }
            });

            function fetchSuggestions(query) {
                fetch(`{{ url('get_suggestions') }}?query=${encodeURIComponent(query)}`) // Changed 'name' to 'query'
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json(); // Expecting JSON response
                    })
                    .then(data => {
                        suggestionsBox.innerHTML = ''; // Clear previous suggestions
                        if (data.length > 0) {
                            data.forEach(item => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('autocomplete-suggestion-item');
                                suggestionItem.textContent = item.medicinename;
                                suggestionItem.onclick = () => selectSuggestion(item.medicinename);
                                suggestionsBox.appendChild(suggestionItem);
                            });
                            suggestionsBox.style.display = 'block';
                        } else {
                            suggestionsBox.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                        suggestionsBox.style.display = 'none';
                        suggestionsBox.innerHTML = '';
                    });
            }

            window.selectSuggestion = function(medName) {
                input.value = medName;
                suggestionsBox.style.display = 'none';
                suggestionsBox.innerHTML = '';
                // Optional: You could trigger the form submission here if desired
                // input.closest('form').submit();
            };
        });
    </script>

</body>

</html>
