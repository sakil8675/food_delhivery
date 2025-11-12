<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit();
}

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Products | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; font-family:'Poppins',sans-serif; }
    h4 { color:#2e7d32; font-weight:600; margin-top:20px; }
    img { border-radius:10px; }
    .btn-edit { background:#2e7d32; color:#fff; }
    .btn-edit:hover { background:#1b5e20; color:#fff; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold">Rahul Cloud Kitchen - Admin</a>
    <div>
      <a href="admin_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>
      <a href="add_product.php" class="btn btn-warning btn-sm">Add Product</a>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h4>All Products</h4>
  <table class="table table-bordered table-hover bg-white mt-3">
    <thead class="table-success">
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Price (₹)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($p = mysqli_fetch_assoc($products)): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><img src="uploads/<?= $p['image'] ?>" width="80"></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= $p['price'] ?></td>
        <td>
          <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-edit btn-sm">Edit</a>
          <a href="delete_product.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
