<?php
require 'db_connection.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // ✅ Check if phone already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE phone='$phone'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "<span class='text-danger'>❌ User already registered!</span> 
                <br><a href='login.php' class='btn btn-link p-0 mt-2'>Login Now</a>";
    } else {
        // ✅ Register new user
        $sql = "INSERT INTO users (name, phone, password) VALUES ('$name', '$phone', '$password')";
        if (mysqli_query($conn, $sql)) {
            $msg = "<span class='text-success'>✅ Registration Successful!</span> 
                    <br><a href='login.php' class='btn btn-link p-0 mt-2'>Login Now</a>";
        } else {
            $msg = "<span class='text-danger'>❌ Something went wrong! Try again.</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Rahul Cloud Kitchen</title>
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
    <h4 class="text-center text-success mb-3">Create Account</h4>

    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" class="form-control mb-3" required>
      <input type="text" name="phone" placeholder="Phone Number" class="form-control mb-3" required>
      <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
      <button class="btn btn-success w-100">Register</button>
    </form>

    <p class="mt-3 text-center"><?= $msg ?></p>
    <p class="text-center text-muted mt-2">Already have an account? 
      <a href="login.php" class="btn-link">Login here</a>
      <br>
      <a href="index.php" class="btn-link">Back to dashboard</a>
    </p>
  </div>
</body>
</html>
