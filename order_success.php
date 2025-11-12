<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require 'db_connection.php';

$user_id = $_SESSION['user_id'];
$address = $_POST['address'];
$location = $_POST['location'];
$phone = $_POST['phone'];

$order_id = "RF-" . str_pad(rand(1,9999), 4, "0", STR_PAD_LEFT);
$total = 0;

// Calculate total
$cart = mysqli_query($conn, "SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=$user_id");
while($row = mysqli_fetch_assoc($cart)) {
  $total += $row['price'];
}

mysqli_query($conn, "INSERT INTO orders (order_id, user_id, address, location, phone, total_amount, status) 
VALUES ('$order_id', $user_id, '$address', '$location', '$phone', '$total', 'Pending')");

// Clear cart
mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Order Success</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center d-flex flex-column align-items-center justify-content-center vh-100">
  <div class="card p-4 shadow" style="width:400px;">
    <h4 class="text-success">✅ Order Placed Successfully!</h4>
    <p>Your Order ID: <strong><?= $order_id ?></strong></p>
    <a href="my_orders.php" class="btn btn-success">Track My Orders</a>
  </div>
</body>
</html>
