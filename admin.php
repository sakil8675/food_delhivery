<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rahul Cloud Kitchen | Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/admin_style.css">
  <style>
    body {
      background: linear-gradient(135deg, #e8f5e9, #ffffff);
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
    }
    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 999;
    }
    .hero {
      text-align: center;
      margin-top: 150px;
    }
    .hero h1 {
      font-size: 2.2rem;
      color: #2e7d32;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .footer {
      position: fixed;
      bottom: 10px;
      text-align: center;
      width: 100%;
      color: #777;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-success shadow">
    <div class="container-fluid">
      <a class="navbar-brand text-white fw-bold fs-4 ms-3">Rahul Cloud Kitchen</a>
      <div class="me-3">
        <a href="admin_login.php" class="btn btn-light btn-sm fw-semibold">Admin Login</a>
      </div>
    </div>
  </nav>

  <!-- Main Section -->
  <div class="hero">
    <h1>Welcome to Rahul Cloud Kitchen Admin Panel</h1>
    <p class="text-muted mt-2">Manage your food items, orders, and delivery — all in one place.</p>
    <a href="admin_login.php" class="btn btn-success mt-3 px-4 py-2 fw-semibold">Go to Login</a>
  </div>

  <!-- Footer -->
  <div class="footer">
    © <?= date("Y") ?> Rahul Cloud Kitchen | All Rights Reserved
  </div>
</body>
</html>
