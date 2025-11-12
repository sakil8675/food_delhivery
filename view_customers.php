<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Details | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; font-family:'Poppins',sans-serif; }
    h4 { color:#2e7d32; font-weight:600; margin-top:20px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold">Rahul Cloud Kitchen - Admin</a>
    <div>
      <a href="admin_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h4>Registered Customer Details</h4>
  <table class="table table-bordered table-hover bg-white mt-3">
    <thead class="table-success">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Password</th>
      </tr>
    </thead>
    <tbody>
      <?php while($u = mysqli_fetch_assoc($users)): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['phone']) ?></td>
        <td><?= htmlspecialchars($u['password']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
