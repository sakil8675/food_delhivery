<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
if ($id) {
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
}
header("Location: view_products.php");
exit();
?>
