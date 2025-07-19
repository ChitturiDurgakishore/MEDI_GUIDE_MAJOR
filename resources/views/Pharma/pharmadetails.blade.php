<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pharmacy Details - Medi-Guide</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;700&display=swap"
        rel="stylesheet" />

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #1a202c;
            color: #d1d5db;
            padding: 2rem 1rem;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .text-lime-neon {
            color: #a3e635;
        }

        .text-cyan-neon {
            color: #22d3ee;
        }

        .bg-dark-card {
            background-color: #2d3748;
        }

        .border-cyan-neon {
            border-color: #0e7490 !important;
        }

        .card-shadow {
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.05);
        }

        .info-label {
            font-weight: 600;
            color: #a3e635;
        }

        iframe {
            border: 0;
            border-radius: 12px;
            width: 100%;
            height: 100%;
            min-height: 300px;
        }

        .details-container {
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .details-container {
                flex-direction: row;
                gap: 2rem;
            }

            .pharmacy-info {
                flex: 1;
            }

            .map-container {
                flex: 1;
            }
        }
    </style>
</head>

<body>
    <div class="container bg-dark-card p-4 p-md-5 rounded-4 card-shadow mx-auto border border-cyan-neon"
        style="max-width: 1000px;">

        <!-- Header -->
        <header class="mb-4 text-center">
            <h1 class="display-5 mb-3 text-lime-neon d-flex align-items-center justify-content-center">
                <svg class="me-3" viewBox="0 0 24 24" fill="none" width="40" height="40"
                    xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="2" width="20" height="20" rx="4" fill="#FF0000" />
                    <path d="M11 5H13V11H19V13H13V19H11V13H5V11H11V5Z" fill="#FFFFFF" />
                </svg>
                Pharmacy Details
            </h1>
        </header>

        <!-- Pharmacy Details and Map Side-by-Side -->
        @if (isset($PharmaDetails))
            <div class="details-container">
                <div class="pharmacy-info text-start">
                    <p><span class="info-label">Pharmacy Name:</span> {{ $PharmaDetails->pharmacy_name }}</p>
                    <p><span class="info-label">Owner Name:</span> {{ $PharmaDetails->owner_name }}</p>
                    <p><span class="info-label">Email:</span> {{ $PharmaDetails->email }}</p>
                    <p><span class="info-label">Phone:</span> {{ $PharmaDetails->phone }}</p>
                    <p><span class="info-label">Address:</span> {{ $PharmaDetails->address }}</p>
                    <p>
                        <span class="info-label">Verified:</span>
                        @if ($PharmaDetails->is_verified == 'true')
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </p>

                    <p><span class="info-label">Registered On:</span>
                        {{ \Carbon\Carbon::parse($PharmaDetails->created_at)->format('d M Y, h:i A') }}</p>
                </div>

                <div class="map-container mt-4 mt-md-0">
                    @php
                        $mapUrl = $PharmaDetails->map_link;
                        $embedUrl =
                            str_replace('www.google.com/maps?q=', 'maps.google.com/maps?q=', $mapUrl) .
                            '&z=15&output=embed';
                    @endphp
                    <iframe src="{{ $embedUrl }}" allowfullscreen loading="lazy"></iframe>
                    <div class="mt-3 text-center">
                        <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer"
                            class="btn btn-primary btn-sm">
                            Navigate
                        </a>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-4 text-center">
                <form action="{{ url('/Result') }}" method="POST" class="text-center mt-4">
                    @csrf
                    <input type="hidden" name="MedicineName"
                        value="{{ $medicinename}}">
                    <button type="submit" class="btn btn-outline-light btn-sm">&larr; Back</button>
                </form>

            </div>
        @else
            <p class="text-danger text-center">Pharmacy details not found.</p>
        @endif

        <!-- Footer -->
        <footer class="mt-5 pt-3 border-top border-secondary text-sm text-secondary text-center">
            <p class="mb-0">&copy; 2025 Medi-Guide. All rights reserved. &copy; KISHORE</p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>
