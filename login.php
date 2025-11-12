<?php
session_start();
require 'db_connection.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE phone='$phone' AND password='$password'");
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: index.php");
        exit();
    } else {
        $msg = "❌ Invalid Phone or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; font-family:'Poppins',sans-serif; }
    .card { border-radius:15px; box-shadow:0 3px 6px rgba(0,0,0,0.1); }
    .btn-link { color:#198754; text-decoration:none; font-weight:500; }
    .btn-link:hover { text-decoration:underline; }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
  <div class="card p-4" style="width:360px;">
    <h4 class="text-center text-success mb-3">User Login</h4>

    <form method="POST">
      <input type="text" name="phone" placeholder="Phone Number" class="form-control mb-3" required>
      <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
      <button class="btn btn-success w-100">Login</button>
    </form>

    <?php if ($msg): ?>
      <p class="text-center mt-3 text-danger"><?= $msg ?></p>
    <?php endif; ?>

    <p class="text-center text-muted mt-3">
      Not registered yet? 
      <a href="register.php" class="btn-link">Register Now</a>
      <br>
      <a href="index.php" class="btn-link">Back to dashboard</a>
    </p>
  </div>
</body>
</html>
