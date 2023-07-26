<?php

if(isset($_POST['add_to_wishlist'])){
   if($user_id == ''){
      header('location:login.php');
   }else{
      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM tb_wishlist WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->bind_param("si", $name, $user_id);
      $check_wishlist_numbers->execute();
      $check_wishlist_numbers->store_result();

      $check_cart_numbers = $conn->prepare("SELECT * FROM tb_cart WHERE name = ? AND user_id = ?");
      $check_cart_numbers->bind_param("si", $name, $user_id);
      $check_cart_numbers->execute();
      $check_cart_numbers->store_result();

      if($check_wishlist_numbers->num_rows > 0){
         $message[] = 'already added to wishlist!';
      }elseif($check_cart_numbers->num_rows > 0){
         $message[] = 'already added to cart!';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO tb_wishlist (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
         $insert_wishlist->bind_param("issds", $user_id, $pid, $name, $price, $image);
         $insert_wishlist->execute();
         $message[] = 'added to wishlist!';
      }
   }
}

if(isset($_POST['add_to_cart'])){
   if($user_id == ''){
      header('location:login.php');
   }else{
      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      $check_cart_numbers = $conn->prepare("SELECT * FROM tb_cart WHERE name = ? AND user_id = ?");
      $check_cart_numbers->bind_param("si", $name, $user_id);
      $check_cart_numbers->execute();
      $check_cart_numbers->store_result();

      if($check_cart_numbers->num_rows > 0){
         $message[] = 'already added to cart!';
      }else{
         $check_wishlist_numbers = $conn->prepare("SELECT * FROM tb_wishlist WHERE name = ? AND user_id = ?");
         $check_wishlist_numbers->bind_param("si", $name, $user_id);
         $check_wishlist_numbers->execute();
         $check_wishlist_numbers->store_result();

         if($check_wishlist_numbers->num_rows > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM tb_wishlist WHERE name = ? AND user_id = ?");
            $delete_wishlist->bind_param("si", $name, $user_id);
            $delete_wishlist->execute();
         }

         $insert_cart = $conn->prepare("INSERT INTO tb_cart (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
         $insert_cart->bind_param("sisiss", $user_id, $pid, $name, $price, $qty, $image);
         $insert_cart->execute();
         $message[] = 'added to cart!';
      }
   }
}

?>
