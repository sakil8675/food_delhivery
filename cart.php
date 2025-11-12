<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
  header("Location: login.php"); 
  exit(); 
}
require 'db_connection.php';
$user_id = $_SESSION['user_id'];

/* ✅ Add to cart if ?add=id passed */
if (isset($_GET['add'])) {
  $pid = intval($_GET['add']);
  $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id=$user_id AND product_id=$pid");
  if (mysqli_num_rows($check) > 0) {
      mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id=$user_id AND product_id=$pid");
  } else {
      mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $pid, 1)");
  }
  header("Location: cart.php");
  exit;
}

/* ✅ Remove from cart */
if (isset($_GET['remove'])) {
  $pid = intval($_GET['remove']);
  mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id AND product_id=$pid");
  header("Location: cart.php");
  exit;
}

/* ✅ Fetch cart items */
$cart = mysqli_query($conn, "
  SELECT c.*, p.name, p.price
  FROM cart c 
  JOIN products p ON c.product_id=p.id 
  WHERE c.user_id=$user_id
");
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Cart | Rahul Cloud Kitchen</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body{background:#f8f9fa;font-family:'Poppins',sans-serif;}
.qty-btn{width:30px;height:30px;border:none;border-radius:6px;font-weight:bold;color:white;}
.btn-minus{background:#ffc107;}
.btn-plus{background:#2e7d32;}
.qty-value{width:45px;text-align:center;border:1px solid #ccc;border-radius:6px;}
</style>
</head>
<body>
<div class="container py-4">
  <h4 class="text-success mb-3">🛒 My Cart</h4>
  <table class="table table-bordered bg-white align-middle text-center" id="cartTable">
    <thead class="table-success">
      <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th>Remove</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($cart)): 
        $sub = $row['price'] * $row['quantity'];
        $total += $sub;
      ?>
      <tr data-id="<?= $row['product_id'] ?>">
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td class="price"><?= $row['price'] ?></td>
        <td>
          <div class="d-flex justify-content-center align-items-center gap-1">
            <button class="qty-btn btn-minus">−</button>
            <input type="text" value="<?= $row['quantity'] ?>" class="qty-value" readonly>
            <button class="qty-btn btn-plus">+</button>
          </div>
        </td>
        <td class="subtotal">₹<?= number_format($sub,2) ?></td>
        <td><a href="cart.php?remove=<?= $row['product_id'] ?>" class="btn btn-danger btn-sm">✖</a></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="d-flex justify-content-between align-items-center mt-3">
    <h5 id="totalText" class="mb-0">Total: ₹<?= number_format($total,2) ?></h5>
    <div>
      <a href="index.php" class="btn btn-secondary btn-sm me-2">⬅️ Back to Dashboard</a>
      <?php if($total > 0): ?>
        <a href="checkout.php" class="btn btn-success btn-sm">Proceed to Checkout</a>
      <?php endif; ?>
    </div>
  </div>

  <?php if($total == 0): ?>
    <p class="text-muted mt-3">Your cart is empty!</p>
  <?php endif; ?>
</div>

<script>
$(".btn-plus, .btn-minus").click(function() {
  let row = $(this).closest("tr");
  let pid = row.data("id");
  let qtyBox = row.find(".qty-value");
  let price = parseFloat(row.find(".price").text());
  let current = parseInt(qtyBox.val());
  let newQty = current;

  if ($(this).hasClass("btn-plus")) newQty++;
  else if (current > 1) newQty--;

  qtyBox.val(newQty);

  // ✅ Update subtotal instantly
  let newSub = (price * newQty).toFixed(2);
  row.find(".subtotal").text("₹" + newSub);

  // ✅ Update total
  updateTotal();

  // ✅ Update in DB
  $.post("cart_update.php", { product_id: pid, quantity: newQty });
});

function updateTotal() {
  let total = 0;
  $("#cartTable .subtotal").each(function() {
    total += parseFloat($(this).text().replace("₹","")) || 0;
  });
  $("#totalText").text("Total: ₹" + total.toFixed(2));
}
</script>
</body>
</html>
