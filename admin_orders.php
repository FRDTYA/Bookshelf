<?php

include('koneksi.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orders</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- custom admin css file link -->
    <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

    <?php include 'admin_header.php';  ?>

    <section class="placed-orders">
        <h1 class="title">placed orders</h1>
        <div class="box-container">
            <?php
            $select_orders = $conn->query("SELECT * FROM `tb_orders`");
            if ($select_orders->num_rows > 0) {
                while ($fetch_orders = $select_orders->fetch_assoc()) {
            ?>
                    <div class="box">
                        <p> user id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
                        <p> placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                        <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                        <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                        <p> number : <span><?php echo $fetch_orders['number']; ?></span> </p>
                        <p> address : <span><?php echo $fetch_orders['address']; ?></span> </p>
                        <p> total_products : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                        <p> total_price : <span>Rp <?php echo number_format($fetch_orders['total_price'], 0, ',', '.') ?></span> </p>
                        <p> payment method : <span><?php echo $fetch_orders['method']; ?></span> </p>

                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                            <select name="update_payment" class="drop-down">
                                <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                                <option value="pending">pending</option>
                                <option value="completed">completed</option>
                            </select>
                            <div class="flex-btn">
                                <input type="submit" name="update_order" class="option-btn" value="update">
                                <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order ?');">delete</a>
                            </div>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no orders placed yet!</p>';
            }
            ?>


        </div>
    </section>






    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

</body>

</html>