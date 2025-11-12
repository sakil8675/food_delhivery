<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = 1;

$exists = mysqli_query($conn, "SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id");
if (mysqli_num_rows($exists) > 0) {
    mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id=$user_id AND product_id=$product_id");
} else {
    mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)");
}

header("Location: cart.php");
exit();
?>
