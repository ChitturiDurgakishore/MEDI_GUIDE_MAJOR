<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pharmacy Registration | Medi-Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            background-color: #1a202c;
            font-family: 'Open Sans', sans-serif;
            color: #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .register-card {
            background-color: #2d3748;
            padding: 3rem;
            border-radius: 1.5rem;
            border: 1px solid #0e7490;
            max-width: 540px;
            width: 100%;
        }

        h2 {
            color: #a3e635;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 2rem;
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

        .btn-neon {
            background-color: #06b6d4;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease-in-out;
        }

        .btn-neon:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 255, 255, 0.2);
        }

        .text-center a {
            color: #22d3ee;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-card text-center">
        <h2>Pharmacy Registration</h2>
        <form action="/Registering" method="POST">
            @csrf
            <div class="mb-3 text-start">
                <label for="pharmacy_name" class="form-label">Pharmacy Name</label>
                <input type="text" class="form-control form-control-dark" id="pharmacy_name" name="pharmacy_name"
                    required>
            </div>
            <div class="mb-3 text-start">
                <label for="owner_name" class="form-label">Owner Name</label>
                <input type="text" class="form-control form-control-dark" id="owner_name" name="owner_name" required>
            </div>
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control form-control-dark" id="email" name="email" required>
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control form-control-dark" id="password" name="password" required>
            </div>
            <div class="mb-3 text-start">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control form-control-dark" id="phone" name="phone" required>
            </div>
            <div class="mb-3 text-start">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control form-control-dark" id="address" name="address" required>
            </div>

            <div id="location-preview" class="text-start mb-3" style="display:none;">
                <label class="form-label">Detected Location:</label>
                <div>
                    <a href="#" target="_blank" id="map_link_display" class="text-cyan-neon">Loading...</a>
                </div>
            </div>
            <!-- Add inside your <form> block before the submit button -->
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="map_link" id="map_link">
            <button type="submit" class="btn btn-neon w-100">Register</button>
        </form>
        <p class="mt-4">Already registered? <a href="/pharmacy-login">Login</a></p>
    </div>

    <script>
        // Auto-detect user's location and generate map link
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                // Populate hidden inputs
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lon;

                // Set Google Maps link
                const mapUrl = `https://www.google.com/maps?q=${lat},${lon}`;
                document.getElementById('map_link').value = mapUrl;

                const linkEl = document.getElementById('map_link_display');
                linkEl.href = mapUrl;
                linkEl.textContent = "View on Google Maps";

                document.getElementById('location-preview').style.display = 'block';
            });
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>
