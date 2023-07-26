<?php
include('koneksi.php');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- custom admin css file link -->
    <link rel="stylesheet" href="css/style.css">
    <title>orders</title>
</head>

<body>

    <?php include 'header.php'; ?>

    <section class="orders">

        <h1 class="heading">placed orders</h1>

        <div class="box-container">

            <?php
            if ($user_id == '') {
                echo '<p class="empty">please login to see your orders</p>';
            } else {
                $order_query = mysqli_query($conn, "SELECT * FROM `tb_orders` WHERE user_id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($order_query) > 0) {
                    while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
            ?>
                        <div class="box">
                            <p>placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
                            <p>name : <span><?= $fetch_orders['name']; ?></span></p>
                            <p>sistem order : <span><?= $fetch_orders['sistem_order']; ?></span></p>
                            <p>email : <span><?= $fetch_orders['email']; ?></span></p>
                            <p>number : <span><?= $fetch_orders['number']; ?></span></p>
                            <p>address : <span><?= $fetch_orders['address']; ?></span></p>
                            <p>payment method : <span><?= $fetch_orders['method']; ?></span></p>
                            <p>your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
                            <p>total price : <span>Rp <?= number_format($fetch_orders['total_price'], 0, ',', '.') ?>,-</span></p>
                            <p> payment status :
                                <span style="color:<?php if ($fetch_orders['payment_status'] == 'pending') {
                                                        echo 'red';
                                                    } else {
                                                        echo 'green';
                                                    } ?>">
                                    <?= $fetch_orders['payment_status']; ?><?php if ($fetch_orders['payment_status'] == 'completed') ?>
                                </span>
                            </p>
                            <?php if ($fetch_orders['payment_status'] == 'completed') { ?>
                                <form action="download_invoice.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?= $fetch_orders['order_id']; ?>">
                                    <button type="submit" class="invoice-button">
                                        <i class="fa-solid fa-download"></i>
                                        <span>invoice</span>
                                    </button>
                                </form>
                            <?php } ?>
                        </div>
            <?php
                    }
                } else {
                    echo '<p class="empty">no orders placed yet!</p>';
                }
            }
            ?>
        </div>

    </section>

    <?php include 'footer.php'; ?>

    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

</body>

</html>
