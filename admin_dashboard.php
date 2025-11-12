<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit();
}

// Count summary
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$pending_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='Pending'"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    .navbar-brand {
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    h4 {
      color: #2e7d32;
      font-weight: 600;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .btn-dashboard {
      background: #2e7d32;
      color: #fff;
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: 500;
      text-decoration: none;
      display: inline-block;
    }
    .btn-dashboard:hover {
      background: #1b5e20;
      color: #fff;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
  <div class="container-fluid">
    <a class="navbar-brand">Rahul Cloud Kitchen - Admin Panel</a>
    <div>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">

  <h4 class="mb-4">Welcome, <?= $_SESSION['admin_name'] ?? 'Admin' ?> 👋</h4>

  <!-- Summary Cards -->
  <div class="row g-3 text-center mb-4">
    <div class="col-md-3 col-6">
      <div class="card p-3">
        <h6>Total Products</h6>
        <h3><?= $total_products ?></h3>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card p-3">
        <h6>Total Orders</h6>
        <h3><?= $total_orders ?></h3>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card p-3">
        <h6>Total Customers</h6>
        <h3><?= $total_customers ?></h3>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card p-3">
        <h6>Pending Orders</h6>
        <h3><?= $pending_orders ?></h3>
      </div>
    </div>
  </div>

  <!-- Action Cards -->
  <div class="row g-3 text-center">
    <div class="col-md-3 col-12">
      <div class="card p-4">
        <h5>Add New Product</h5>
        <p class="text-muted">Add food item with name, price & image.</p>
        <a href="add_product.php" class="btn-dashboard">Add Product</a>
      </div>
    </div>
    <div class="col-md-3 col-12">
      <div class="card p-4">
        <h5>View / Edit Products</h5>
        <p class="text-muted">See all products and edit or delete.</p>
        <a href="view_products.php" class="btn-dashboard">Manage Products</a>
      </div>
    </div>
    <div class="col-md-3 col-12">
      <div class="card p-4">
        <h5>Manage Orders</h5>
        <p class="text-muted">View all orders and update their status.</p>
        <a href="manage_orders.php" class="btn-dashboard">View Orders</a>
      </div>
    </div>
    <div class="col-md-3 col-12">
      <div class="card p-4">
        <h5>Customer Details</h5>
        <p class="text-muted">View registered users info.</p>
        <a href="view_customers.php" class="btn-dashboard">Show Customers</a>
      </div>
    </div>
  </div>

</div>
</body>
</html>
