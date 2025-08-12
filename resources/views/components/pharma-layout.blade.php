<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pharmacy Dashboard - Medi-Guide</title>

  <!-- Bootstrap + Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #0f172a;
      color: #e2e8f0;
    }

    .header {
      background-color: #1a202c;
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #0ea5e9;
      height: 72px;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header-title {
      color: #a3e635;
      font-size: 1.5rem;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    .pharmacy-name {
      color: #38bdf8;
      font-weight: 600;
      margin-right: 1rem;
      font-size: 0.95rem;
    }

    .logout-btn {
      background-color: #ef4444;
      border: none;
      padding: 0.4rem 0.9rem;
      border-radius: 8px;
      color: white;
      font-size: 0.875rem;
      transition: background-color 0.3s;
      cursor: pointer;
    }

    .logout-btn:hover {
      background-color: #dc2626;
    }

    .layout {
      display: flex;
      height: calc(100vh - 72px);
    }

    .sidebar {
      background-color: #1e293b;
      width: 240px;
      padding: 2rem 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      flex-shrink: 0;
      height: 100vh;
      position: sticky;
      top: 72px;
      overflow-y: auto;
      transition: transform 0.3s ease-in-out;
    }

    .sidebar.collapsed {
      transform: translateX(-100%);
      position: fixed;
      z-index: 999;
    }

    .sidebar .nav a {
      padding: 1rem 1rem;
      color: #cbd5e1;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: background 0.2s, color 0.2s;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      font-size: 1rem;
    }

    .sidebar .nav a:hover {
      background-color: #0ea5e9;
      color: #fff;
    }

    .main-content {
      flex: 1;
      padding: 2rem;
      background-color: #0f172a;
      color: #d1d5db;
      overflow-y: auto;
    }

    /* Neon text colors */
    .text-lime-neon {
      color: #a3e635;
      text-shadow: 0 0 8px #a3e635aa;
    }

    .text-cyan-neon {
      color: #22d3ee;
      text-shadow: 0 0 8px #22d3eeaa;
    }

    .main-content::-webkit-scrollbar {
      width: 8px;
    }

    .main-content::-webkit-scrollbar-track {
      background: #1e293b;
    }

    .main-content::-webkit-scrollbar-thumb {
      background-color: #0ea5e9;
      border-radius: 10px;
      border: 2px solid #1e293b;
    }

    .sidebar-toggle {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: #a3e635;
      margin-right: 1rem;
    }

    @media (max-width: 768px) {
      .layout {
        flex-direction: column;
        height: auto;
      }

      .sidebar {
        width: 240px;
        height: 100%;
        position: fixed;
        top: 72px;
        left: 0;
        z-index: 1050;
        transform: translateX(-100%);
      }

      .sidebar.show {
        transform: translateX(0);
      }

      .sidebar-toggle {
        display: inline-block;
      }

      .main-content {
        padding: 1rem;
        overflow-y: visible;
      }
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header class="header">
    <div class="d-flex align-items-center">
      <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
      <div class="header-title">Medi-Guide</div>
    </div>
    <div class="d-flex align-items-center">
      <span class="pharmacy-name">{{ session('Pharmacy') }}</span>
      <form action="{{ url('/logout') }}" method="get" class="mb-0">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </header>

  <!-- Layout -->
  <div class="layout">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <nav class="nav flex-column">
        <a href="{{ url('/dashboard') }}"><i class="bi bi-app"></i> DashBoard</a>
        <a href="{{ url('/pharmacy/inventory') }}"><i class="bi bi-box-seam"></i> Inventory</a>
        <a href="{{ url('/pharmacy/entry') }}"><i class="bi bi-pencil-square"></i> Entry</a>
        <a href="{{ url('/pharmacy/adjust') }}"><i class="bi bi-sliders"></i> Adjust</a>
        <a href="{{ url('/pharmacy/import') }}"><i class="bi bi-cloud-arrow-down"></i> Import</a>
        <a href="{{ url('/pharmacy/predictions') }}"><i class="bi bi-question-diamond"></i> Predictions</a>
        <a href="{{ url('/medicine-request') }}"><i class="bi bi-arrow-down-right-square"></i> Requests</a>
        <a href="{{ url('/pharmacy/history') }}"><i class="bi bi-hourglass-split"></i> History</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      {{ $MainContent }}
    </main>
  </div>
  <!-- Bootstrap JS + Sidebar Toggle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      sidebar.classList.toggle("show");
    }
  </script>
</body>
</html>
