<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
if (!$id) {
    die("Invalid order ID");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE id=$id");
    header("Location: manage_orders.php");
    exit();
}

// Fetch selected order
$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE id=$id"));
if (!$order) die("Order not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Order | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f9f9f9; font-family: 'Poppins', sans-serif; }
    .card { border-radius:15px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-top:40px; }
    h4 { color:#2e7d32; font-weight:600; }
  </style>
</head>
<body>
<div class="container">
  <div class="card p-4">
    <h4>Update Order Status</h4>
    <p><strong>Order ID:</strong> <?= $order['order_id'] ?><br>
       <strong>User ID:</strong> <?= $order['user_id'] ?><br>
       <strong>Total Amount:</strong> ₹<?= $order['total_amount'] ?><br>
       <strong>Current Status:</strong> <?= $order['status'] ?></p>

    <form method="POST">
      <label class="form-label">Select New Status:</label>
      <select name="status" class="form-select mb-3" required>
        <option <?= $order['status']=='Pending'?'selected':'' ?>>Pending</option>
        <option <?= $order['status']=='Preparing'?'selected':'' ?>>Preparing</option>
        <option <?= $order['status']=='Packed'?'selected':'' ?>>Packed</option>
        <option <?= $order['status']=='Out for Delivery'?'selected':'' ?>>Out for Delivery</option>
        <option <?= $order['status']=='Delivered'?'selected':'' ?>>Delivered</option>
        <option <?= $order['status']=='Rejected'?'selected':'' ?>>Rejected</option>
      </select>
      <button class="btn btn-success">Update Status</button>
      <a href="manage_orders.php" class="btn btn-secondary">Back</a>
    </form>
  </div>
</div>
</body>
</html>
