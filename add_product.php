<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $img = $_FILES['image']['name'];
    $target = "uploads/" . basename($img);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $sql = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$img')";
    if (mysqli_query($conn, $sql)) {
        $msg = "✅ Product Added Successfully!";
    } else {
        $msg = "❌ Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product | Rahul Cloud Kitchen</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h4 class="text-success mb-4">Add New Product</h4>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <input type="text" name="name" class="form-control" placeholder="Product Name" required>
    </div>
    <div class="mb-3">
      <input type="number" name="price" class="form-control" placeholder="Price (₹)" step="0.01" required>
    </div>
    <div class="mb-3">
      <input type="file" name="image" class="form-control" required>
    </div>
    <button class="btn btn-success">Add Product</button>
  </form>
  <p class="mt-3 text-success"><?= $msg ?></p>
  <a href="admin_dashboard.php" class="btn btn-secondary mt-2">← Back to Dashboard</a>
</div>
</body>
</html>
