<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['user_id'])) exit();
$user_id = $_SESSION['user_id'];
$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id AND product_id=$id");
header("Location: cart.php");
exit();
?>
