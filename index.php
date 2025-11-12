<?php
session_start();
require 'db_connection.php';
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rahul Cloud Kitchen | Menu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; font-family:'Poppins',sans-serif; }
    .card { border-radius:15px; box-shadow:0 3px 6px rgba(0,0,0,0.1); transition: transform 0.2s ease-in-out; }
    .card:hover { transform: scale(1.03); }
    .card-img-top { width:100%; height:180px; object-fit:cover; border-radius:10px; }
    .price { font-weight:600; color:#2e7d32; }
    .btn-cart { background:#2e7d32; color:white; border:none; border-radius:10px; padding:8px 15px; font-weight:500; }
    .btn-cart:hover { background:#1b5e20; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold">Rahul Cloud Kitchen</a>
    <div>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="cart.php" class="btn btn-light btn-sm">🛒 Cart</a>
        <a href="my_orders.php" class="btn btn-warning btn-sm">My Orders</a>
        <a href="user_logout.php" class="btn btn-danger btn-sm">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-light btn-sm">Login</a>
        <a href="register.php" class="btn btn-warning btn-sm">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h4 class="text-success mb-3">Our Delicious Items 🍴</h4>
  <div class="row g-3">
    <?php while($p = mysqli_fetch_assoc($products)): ?>
      <div class="col-md-3 col-6">
        <div class="card text-center p-2">
          <!-- ✅ Image directly from uploads folder -->
          <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>">
          <div class="card-body">
            <h6 class="mt-2"><?= htmlspecialchars($p['name']) ?></h6>
            <p class="price">₹<?= $p['price'] ?></p>

            <?php if(isset($_SESSION['user_id'])): ?>
              <form method="POST" action="cart_add.php">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button class="btn-cart w-100">Add to Cart</button>
              </form>
            <?php else: ?>
              <a href="login.php" class="btn btn-outline-success btn-sm">Login to Order</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
