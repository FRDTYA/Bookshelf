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
    <title>admin panel</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- custom admin css file link -->
    <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

    <?php include 'admin_header.php';  ?>

    <!-- admin dashboard section start -->

    <section class="dashboard">
        <h1 class="title">dashboard</h1>
        <div class="box-container">

            <div class="box">
                <?php
                $total_pendings = 0;
                $select_pendings = mysqli_query($conn, "SELECT total_price FROM `tb_orders` WHERE payment_status = 'pending'")
                    or die('query failed');
                if (mysqli_num_rows($select_pendings) > 0) {
                    while ($fetch_pendings = mysqli_fetch_assoc($select_pendings)) {
                        $total_price = $fetch_pendings['total_price'];
                        $total_pendings += $total_price;
                    };
                };
                ?>
                <h3><?php echo 'Rp ' . number_format($total_pendings, 0, ',', '.'); ?></h3>
                <a href="admin_orders_new.php">
                <p>total pendings</p>
                </a>
            </div>


            <div class="box">
                <?php
                $total_completed = 0;
                $select_completed = mysqli_query($conn, "SELECT total_price FROM `tb_orders` WHERE payment_status = 'completed'")
                    or die('query failed');
                if (mysqli_num_rows($select_completed) > 0) {
                    while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
                        $total_price = $fetch_completed['total_price'];
                        $total_completed += $total_price;
                    };
                };
                ?>
                <h3><?php echo 'Rp ' . number_format($total_completed, 0, ',', '.'); ?></h3>
                <a href="admin_orders_new.php">
                <p>completed payments</p>
                </a>
            </div>


            <div class="box">
                <?php
                $select_orders = mysqli_query($conn, "SELECT * FROM `tb_orders`") or die('query failed');
                $number_of_orders = mysqli_num_rows($select_orders);
                ?>
                <h3><?php echo $number_of_orders; ?></h3>
                <a href="admin_orders_new.php">
                <p>order placed</p>
                </a>
            </div>

            <div class="box">
                <?php
                $select_products = mysqli_query($conn, "SELECT * FROM `tb_products`") or die('query failed');
                $number_of_products = mysqli_num_rows($select_products);
                ?>
                <h3><?php echo $number_of_products; ?></h3>
                <a href="admin_products.php">
                <p>products added</p>
                </a>
            </div>


            <div class="box">
                <?php
                $select_user = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE user_type = 'user'")
                    or die('query failed');
                $number_of_user = mysqli_num_rows($select_user);
                ?>
                <h3><?php echo $number_of_user; ?></h3>
                <a href="admin_users.php">
                <p>normal user</p>
                </a>
            </div>

            <div class="box">
                <?php
                $select_admin = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE user_type = 'admin'")
                    or die('query failed');
                $number_of_admin = mysqli_num_rows($select_admin);
                ?>
                <h3><?php echo $number_of_admin; ?></h3>
                <a href="admin_users.php">
                <p>admin user</p>
                </a>
            </div>

            <div class="box">
                <?php
                $select_account = mysqli_query($conn, "SELECT * FROM `tb_user`")
                    or die('query failed');
                $number_of_account = mysqli_num_rows($select_account);
                ?>
                <h3><?php echo $number_of_account; ?></h3>
                <a href="admin_users.php">
                <p>total user</p>
                </a>
            </div>

            <div class="box">
                <?php
                $select_messages = mysqli_query($conn, "SELECT * FROM `tb_message`")
                    or die('query failed');
                $number_of_messages = mysqli_num_rows($select_messages);
                ?>
                <h3><?php echo $number_of_messages; ?></h3>
                <a href="admin_contacts.php">
                <p>new messages</p>
                </a>
            </div>

        </div>
    </section>

    <!-- admin dashboard section ends -->




    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

</body>

</html>