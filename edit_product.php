<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
if (!$id) {
    die("Invalid product ID");
}

// বর্তমান প্রোডাক্ট ডেটা আনো
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$id"));
if (!$product) {
    die("Product not found!");
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $product['image']; // default old image

    // যদি নতুন ছবি আপলোড হয়
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $update = "UPDATE products SET name='$name', price='$price', image='$image' WHERE id=$id";
    if (mysqli_query($conn, $update)) {
        $msg = "✅ Product Updated Successfully!";
        // refresh current data
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$id"));
    } else {
        $msg = "❌ Error updating product!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f9f9f9; font-family:'Poppins',sans-serif; }
    .card { border-radius:15px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-top:40px; }
    h4 { color:#2e7d32; font-weight:600; }
  </style>
</head>
<body>
<div class="container">
  <div class="card p-4">
    <h4>Edit Product</h4>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Price (₹)</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" class="form-control" step="0.01" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        <img src="uploads/<?= $product['image'] ?>" width="100" class="mb-2 rounded">
        <input type="file" name="image" class="form-control">
      </div>
      <button class="btn btn-success">Update Product</button>
      <a href="view_products.php" class="btn btn-secondary">Back</a>
    </form>
    <?php if($msg): ?>
      <div class="alert alert-info mt-3"><?= $msg ?></div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
