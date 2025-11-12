<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
  header("Location: login.php"); 
  exit(); 
}
require 'db_connection.php';
$user_id = $_SESSION['user_id'];

$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id=$user_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f8f9fa;font-family:'Poppins',sans-serif;}
    .table th,.table td{text-align:center;vertical-align:middle;}
    .order-items{text-align:left;font-size:13px;color:#444;}
  </style>
</head>
<body>
<div class="container py-4">
  <h4 class="text-success text-center mb-3">📦 My Orders</h4>

  <div class="d-flex justify-content-between mb-3">
    <button onclick="location.reload()" class="btn btn-success btn-sm">🔄 Refresh</button>
    <a href="index.php" class="btn btn-secondary btn-sm">⬅️ Back to Dashboard</a>
  </div>

  <table class="table table-bordered bg-white shadow-sm">
    <thead class="table-success">
      <tr>
        <th>Order ID</th>
        <th>Items</th>
        <th>Total</th>
        <th>Status</th>
        <th>Location</th>
      </tr>
    </thead>
    <tbody>
      <?php while($o = mysqli_fetch_assoc($orders)): 
        $orderId = mysqli_real_escape_string($conn, $o['order_id']);

        // ✅ Get items for this order
        $itemQ = "
          SELECT p.name, oi.quantity 
          FROM order_items oi 
          JOIN products p ON oi.product_id = p.id 
          WHERE oi.order_id='$orderId'";
        $itemR = mysqli_query($conn, $itemQ);

        $itemsList = "";
        if (mysqli_num_rows($itemR) > 0) {
          while($i = mysqli_fetch_assoc($itemR)) {
            $itemsList .= "• " . htmlspecialchars($i['name']) . " × " . $i['quantity'] . "<br>";
          }
        } else {
          $itemsList = "<i class='text-muted'>No Items</i>";
        }

        // ✅ Calculate total
        $totalQ = "
          SELECT SUM(p.price * oi.quantity) AS total 
          FROM order_items oi 
          JOIN products p ON oi.product_id = p.id 
          WHERE oi.order_id='$orderId'";
        $totalR = mysqli_fetch_assoc(mysqli_query($conn, $totalQ));
        $total = $totalR['total'] ?: $o['total_amount'];

        $color = match($o['status']) {
          'Pending'=>'secondary','Preparing'=>'info','Packed'=>'primary',
          'Out for Delivery'=>'warning','Delivered'=>'success','Rejected'=>'danger',
          default=>'dark'
        };
      ?>
      <tr>
        <td><?= htmlspecialchars($o['order_id']) ?></td>
        <td class="order-items"><?= $itemsList ?></td>
        <td>₹<?= number_format($total,2) ?></td>
        <td><span class="badge bg-<?= $color ?>"><?= htmlspecialchars($o['status']) ?></span></td>
        <td>
          <?php if(!empty($o['location'])): ?>
            <a href="https://www.google.com/maps?q=<?= htmlspecialchars($o['location']) ?>" 
               target="_blank" class="btn btn-outline-success btn-sm">📍 View</a>
          <?php else: ?>
            <span class="text-muted">No Location</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
