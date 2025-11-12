<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit();
}
require 'db_connection.php';

/* ✅ Handle AJAX Status Update */
if (isset($_POST['ajax']) && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $update = mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$id");
    echo $update ? "success" : "error";
    exit;
}

/* ✅ Fetch Orders + User Info */
$query = "
SELECT o.*, u.name AS customer_name, u.phone AS customer_phone
FROM orders o
JOIN users u ON o.user_id = u.id
ORDER BY o.id DESC
";
$orders = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body { background:#f8f9fa; font-family:'Poppins',sans-serif; }
    .table th, .table td { vertical-align: middle; text-align:center; }
    h4 { color:#2e7d32; font-weight:600; margin-top:20px; }
    .order-products { text-align:left; font-size:13px; color:#444; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold">Rahul Cloud Kitchen - Admin</a>
    <div>
      <a href="admin_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>
      <a href="add_product.php" class="btn btn-warning btn-sm">Add Product</a>
      <a href="admin_logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h4>Manage Orders</h4>
  <table class="table table-bordered table-hover bg-white align-middle text-center">
    <thead class="table-success">
      <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Address & Items</th>
        <th>Total</th>
        <th>Status</th>
        <th>Location</th>
        <th>Accept / Reject</th>
        <th>Progress</th>
      </tr>
    </thead>
    <tbody>
      <?php while($o = mysqli_fetch_assoc($orders)): ?>
        <?php
          // ✅ Product Name + Quantity fetch
          $itemsQ = "
            SELECT p.name, oi.quantity 
            FROM order_items oi 
            JOIN products p ON oi.product_id=p.id 
            WHERE oi.order_id='{$o['order_id']}'
          ";
          $itemsR = mysqli_query($conn, $itemsQ);
          $productList = "";
          if (mysqli_num_rows($itemsR) > 0) {
              while($it = mysqli_fetch_assoc($itemsR)) {
                  $productList .= "• " . htmlspecialchars($it['name']) . " × " . $it['quantity'] . "<br>";
              }
          } else {
              $productList = "<i class='text-muted'>No Products</i>";
          }

          // ✅ Location
          $coords = explode(',', $o['location']);
          $mapLink = (count($coords)==2) ? "https://www.google.com/maps?q=".trim($coords[0]).",".trim($coords[1]) : "#";

          // ✅ Status color
          $statusColor = match($o['status']) {
              'Pending'=>'secondary',
              'Preparing'=>'info',
              'Packed'=>'primary',
              'Out for Delivery'=>'warning',
              'Delivered'=>'success',
              'Rejected'=>'danger',
              default=>'dark'
          };
        ?>
        <tr id="row-<?= $o['id'] ?>">
          <td><?= htmlspecialchars($o['order_id']) ?></td>
          <td><?= htmlspecialchars($o['customer_name']) ?></td>
          <td><?= htmlspecialchars($o['customer_phone']) ?></td>
          <td class="order-products">
            <b>📍 <?= htmlspecialchars($o['address']) ?></b><br>
            <?= $productList ?>
          </td>
          <td>₹<?= number_format($o['total_amount'],2) ?></td>
          <td><span class="badge bg-<?= $statusColor ?>" id="status-<?= $o['id'] ?>"><?= htmlspecialchars($o['status']) ?></span></td>
          <td>
            <?php if(!empty($o['location'])): ?>
              <a href="<?= $mapLink ?>" target="_blank" class="btn btn-outline-success btn-sm">Map</a>
            <?php else: ?><span class="text-muted">No Location</span><?php endif; ?>
          </td>
          <td>
            <button class="btn btn-success btn-sm" onclick="updateStatus(<?= $o['id'] ?>,'Preparing')">Accept</button>
            <button class="btn btn-danger btn-sm" onclick="updateStatus(<?= $o['id'] ?>,'Rejected')">Reject</button>
          </td>
          <td>
            <div class="btn-group btn-group-sm">
              <button class="btn btn-info" onclick="updateStatus(<?= $o['id'] ?>,'Packed')">Packed</button>
              <button class="btn btn-warning" onclick="updateStatus(<?= $o['id'] ?>,'Out for Delivery')">Out</button>
              <button class="btn btn-dark" onclick="updateStatus(<?= $o['id'] ?>,'Delivered')">Delivered</button>
            </div>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
function updateStatus(id, status) {
  if (!confirm("Mark this order as '"+status+"'?")) return;
  $.post("manage_orders.php", {ajax:1, id:id, status:status}, function(res){
    if(res.trim()=="success"){
      $("#status-"+id).text(status).removeClass().addClass("badge bg-success");
    } else alert("❌ Failed to update!");
  });
}
</script>
</body>
</html>
