<?php

include('koneksi.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
};

if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
    $update_orders = $conn->prepare("UPDATE `tb_orders` SET payment_status = ? 
    WHERE id = ?");
    $update_orders->execute([$update_payment, $order_id]);
    $message[] = 'payment has been updated!';
};

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_orders = $conn->prepare("DELETE FROM `tb_orders` WHERE id = ?");
    $delete_orders->execute([$delete_id]);
    header('location:admin_orders_new.php');
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
                    // Mengambil informasi gambar ulasan
                    $select_review = $conn->query("SELECT * FROM `tb_orders` WHERE `review_image` = {$fetch_orders['id']} LIMIT 1");
                    $fetch_review = $select_review->fetch_assoc();
            ?>
                    <div class="box <?php echo $fetch_orders['payment_status']; ?>">
                        <p> user id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
                        <p> placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                        <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                        <p> sistem order : <span><?php echo $fetch_orders['sistem_order']; ?></span> </p>
                        <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                        <p> number : <span><?php echo $fetch_orders['number']; ?></span> </p>
                        <p> address : <span><?php echo $fetch_orders['address']; ?></span> </p>
                        <p> total_products : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                        <p> total_price : <span>Rp <?php echo number_format($fetch_orders['total_price'], 0, ',', '.') ?></span> </p>
                        <p> payment method : <span><?php echo $fetch_orders['method']; ?></span> </p>

                        <?php if ($fetch_orders['payment_status'] == 'completed') { ?>
                        <p> status payment : <span><?php echo $fetch_orders['payment_status']; ?></span> </p>
                        <?php } ?>

                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">

                            <?php if ($fetch_orders['payment_status'] == 'pending') { ?>
                            <select name="update_payment" class="drop-down" onchange="updatePaymentStatus(this)">
                                <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                            <?php } ?>

                            <?php if (!empty($fetch_orders['review_image'])) : ?>
                                <a href="upload_img/<?php echo $fetch_orders['review_image']; ?>" target="_blank" class="bukti-btn">Bukti Transaksi</a>
                            <?php endif; ?>

                            <?php if ($fetch_orders['payment_status'] == 'pending') { ?>
                            <div class="flex-btn">
                                <input type="submit" name="update_order" class="option-btn" value="update">
                                <a href="admin_orders_new.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order ?');">delete</a>
                            </div>
                            <?php } ?>
                        </form>                        

                        <?php if ($fetch_orders['payment_status'] == 'completed') { ?>
                                <form action="admin_invoice.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                                    <button type="submit" class="invoice-button">
                                        <i class="fa-solid fa-download"></i>
                                        <span class="aktif">invoice</span>
                                    </button>
                                </form>
                            <?php } ?>

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