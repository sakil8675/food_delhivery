<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
  header("Location: login.php"); 
  exit(); 
}
require 'db_connection.php';

$user_id = $_SESSION['user_id'];

// ✅ Fetch Cart Items
$cartItems = mysqli_query($conn, "
  SELECT c.product_id, c.quantity, p.price 
  FROM cart c 
  JOIN products p ON c.product_id = p.id 
  WHERE c.user_id=$user_id
");

$total = 0;
while($item = mysqli_fetch_assoc($cartItems)){
  $total += $item['price'] * $item['quantity'];
}
mysqli_data_seek($cartItems, 0); // Reset pointer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $location = mysqli_real_escape_string($conn, $_POST['location']);
  
  // ✅ Generate Order ID
  $last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM orders ORDER BY id DESC LIMIT 1"));
  $next = $last ? $last['id'] + 1 : 1;
  $order_id = "RF-" . str_pad($next, 4, "0", STR_PAD_LEFT);
  
  // ✅ Insert order
  $insertOrder = "INSERT INTO orders (order_id, user_id, address, phone, total_amount, location, status) 
                  VALUES ('$order_id', '$user_id', '$address', '$phone', '$total', '$location', 'Pending')";
  if (mysqli_query($conn, $insertOrder)) {
    while($item = mysqli_fetch_assoc($cartItems)) {
      $pid = $item['product_id'];
      $qty = $item['quantity'];
      mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('$order_id', $pid, $qty)");
    }
    mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id");
    header("Location: my_orders.php");
    exit;
  } else {
    echo "<script>alert('❌ Failed to place order!');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
          document.getElementById('location').value = pos.coords.latitude + "," + pos.coords.longitude;
        }, function() {
          alert("Please allow location access!");
        });
      } else alert("Geolocation not supported.");
    }
  </script>
  <style>
    body{
      background:#f8f9fa;
      font-family:'Poppins',sans-serif;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      margin:0;
    }
    .card{
      border:none;
      box-shadow:0 3px 8px rgba(0,0,0,0.1);
      border-radius:12px;
      width:100%;
      max-width:450px;
      padding:20px 25px;
    }
    .total-box{font-size:18px;font-weight:600;color:#2e7d32;margin-bottom:10px;}
    small.text-danger{font-size:13px;}
  </style>
</head>
<body>
  <div class="card">
    <h4 class="text-success mb-3 text-center">Checkout</h4>

    <form method="POST">
      <div class="mb-2">
        <label class="form-label">Delivery Address</label>
        <textarea name="address" class="form-control" required rows="2"></textarea>
      </div>

      <div class="mb-2">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Current Location</label>
        <div class="input-group">
          <input type="text" id="location" name="location" class="form-control" placeholder="Click to get location" readonly required>
          <button type="button" class="btn btn-outline-success" onclick="getLocation()">Get</button>
        </div>
      </div>

      <div class="text-center mb-3">
        <img src="qr.jpg" width="120" class="mb-2"><br>
        <small class="text-muted d-block">Scan this QR to pay the amount below</small>
      </div>

      <!-- ✅ Total Amount -->
      <div class="text-center total-box">
        Total Payable: ₹<?= number_format($total, 2) ?>
      </div>

      <button type="submit" class="btn btn-success w-100 mb-2">Confirm Order</button>

      <!-- ✅ Disclaimer -->
      <div class="text-center">
        <small class="text-danger">
          ⚠️ Please pay exact amount.<br>
          For payment issues, call <b>7319428078</b>.
        </small>
      </div>
    </form>
  </div>
</body>
</html>
