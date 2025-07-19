<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Medi-Guide Home</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />

  <!-- Google Fonts: Inter + Space Grotesk -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Space+Grotesk:wght@400;700&display=swap" rel="stylesheet" />
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;700&display=swap" rel="stylesheet">
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
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 1rem;
      box-sizing: border-box;
    }

h1, h2, h3 {
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  letter-spacing: 0.5px;
  line-height: 1.3;
}


    p, li {
      letter-spacing: 0.2px;
      line-height: 1.6;
    }

    .text-lime-neon { color: #a3e635; }
    .text-cyan-neon { color: #22d3ee; }
    .bg-cyan-neon { background-color: #06b6d4; }
    .border-cyan-neon { border-color: #0e7490 !important; }
    .bg-dark-card { background-color: #2d3748; }
    .bg-dark-feature { background-color: #4a5568; }
    .border-feature-neon { border-color: #0e7490 !important; }

    .btn-neon {
      transition: all 0.3s ease-in-out;
      font-weight: 600;
      border-radius: 999px;
    }

    .btn-neon:hover {
      transform: translateY(-2px);
    }

    .card-shadow {
      box-shadow: none;
    }
  </style>
</head>
<body class="selection:bg-info selection:text-white">
  <div class="container bg-dark-card p-4 p-md-5 rounded-4 card-shadow mx-auto text-center border border-cyan-neon">

    <!-- Header -->
    <header class="mb-4">
      <h1 class="display-4 mb-3 text-lime-neon d-flex align-items-center justify-content-center">
        <svg class="me-3" viewBox="0 0 24 24" fill="none" width="40" height="40" xmlns="http://www.w3.org/2000/svg">
          <rect x="2" y="2" width="20" height="20" rx="4" fill="#FF0000"/>
          <path d="M11 5H13V11H19V13H13V19H11V13H5V11H11V5Z" fill="#FFFFFF"/>
        </svg>
        Medi-Guide
      </h1>
    </header>

    <!-- Features -->
    <section class="mb-5">
      <h2 class="fs-2 text-lime-neon mb-4 text-center">How Medi-Guide Helps</h2>
      <div class="row row-cols-1 row-cols-md-2 g-4 justify-content-center text-start">
        <!-- Customers -->
        <div class="col">
          <div class="bg-dark-feature p-4 rounded-3 border border-feature-neon">
            <h3 class="fs-5 text-cyan-neon text-center">For Customers</h3>
            <ul class="list-unstyled text-light">
              <li> ▪️ Medicine availability near you.</li>
              <li> ▪️ Real-time prices.</li>
              <li> ▪️ Alternative medicine suggestions & prices.</li>
              <li> ▪️ Informed buying decisions.</li>
            </ul>
          </div>
        </div>
        <!-- Pharmacies -->
        <div class="col">
          <div class="bg-dark-feature p-4 rounded-3 border border-feature-neon">
            <h3 class="fs-5 text-cyan-neon text-center">For Pharmacies</h3>
            <ul class="list-unstyled text-light">
              <li> ▪️ Efficient inventory management.</li>
              <li> ▪️ Update stock levels easily.</li>
              <li> ▪️ Add/remove medicines.</li>
              <li> ▪️ Bulk updates via CSV.</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="mt-4 d-flex flex-column flex-sm-row justify-content-center gap-3">
        <a href="/Search"><button class="btn btn-lg bg-cyan-neon text-white btn-neon">Search Medicine</button></a>
       <a href="/Login"><button class="btn btn-lg bg-dark-feature text-lime-neon btn-neon border border-feature-neon">Pharmacy Login</button></a>
      </div>
    </section>

    <!-- Footer -->
    <footer class="mt-5 pt-3 border-top border-secondary text-sm text-secondary">
      <p class="mb-2">
        &copy; 2025 Medi-Guide. All rights reserved. &copy; KISHORE
      </p>
    </footer>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
