<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pharmacy Login | Medi-Guide</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;700&display=swap" rel="stylesheet">
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
    .login-card {
      background-color: #2d3748;
      padding: 3rem;
      border-radius: 1.5rem;
      border: 1px solid #0e7490;
      max-width: 420px;
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
  <div class="login-card text-center">
    <h2>Pharmacy Login</h2>
    <form action="/PharmaLogin" method="get">
      <div class="mb-3 text-start">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control form-control-dark" id="email" name="email" required>
      </div>
      <div class="mb-4 text-start">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control form-control-dark" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-neon w-100">Login</button>
    </form>
    <p class="mt-4">Don't have an account? <a href="/Register">Register</a></p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
