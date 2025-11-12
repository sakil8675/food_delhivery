<?php
session_start();
require 'db_connection.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple database check (no hash)
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_name'] = $row['username'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $msg = "❌ Invalid Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | Rahul Cloud Kitchen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #e8f5e9, #ffffff);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
    }
    .login-box {
      background: #fff;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 350px;
    }
    h4 {
      color: #2e7d32;
      font-weight: 600;
      text-align: center;
    }
    .btn-success {
      background-color: #2e7d32;
      border: none;
    }
    .btn-success:hover {
      background-color: #1b5e20;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h4>Admin Login</h4>
    <form method="POST">
      <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
      <button class="btn btn-success w-100">Login</button>
    </form>
    <?php if($msg): ?>
      <div class="alert alert-danger mt-3 text-center"><?= $msg ?></div>
    <?php endif; ?>
    <div class="text-center mt-3">
      <a href="index.php" class="text-decoration-none text-success">← Back to Home</a>
    </div>
  </div>
</body>
</html>
