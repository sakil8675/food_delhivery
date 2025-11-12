<?php
session_start();
require 'db_connection.php';
if(isset($_POST['product_id']) && isset($_POST['quantity'])){
  $pid = intval($_POST['product_id']);
  $qty = intval($_POST['quantity']);
  $user_id = $_SESSION['user_id'];
  if($qty <= 0){
    mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id AND product_id=$pid");
  } else {
    mysqli_query($conn, "UPDATE cart SET quantity=$qty WHERE user_id=$user_id AND product_id=$pid");
  }
}
?>
