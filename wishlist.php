<?php

include('koneksi.php');
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
};

include 'wishlist_cart.php';

if (isset($_POST['delete'])) {
    $wishlist_id = $_POST['wishlist_id'];
    $delete_wishlist_item = $conn->prepare("DELETE FROM `tb_wishlist` WHERE id = ?");
    $delete_wishlist_item->bind_param("i", $wishlist_id);
    $delete_wishlist_item->execute();
}

if (isset($_GET['delete_all'])) {
    $delete_wishlist_item = $conn->prepare("DELETE FROM `tb_wishlist` WHERE user_id = ?");
    $delete_wishlist_item->bind_param("i", $user_id);
    $delete_wishlist_item->execute();
    header('location:wishlist.php');
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
    <title>Wishlist</title>
</head>

<body>

    <?php include 'header.php'; ?>

    <section class="products">

        <h3 class="heading">your wishlist</h3>

        <div class="box-container">

            <?php
            $grand_total = 0;
            $select_wishlist = $conn->prepare("SELECT * FROM `tb_wishlist` WHERE user_id = ?");
            $select_wishlist->bind_param("i", $user_id);
            $select_wishlist->execute();
            $result_wishlist = $select_wishlist->get_result();
            if ($result_wishlist->num_rows > 0) {
                while ($fetch_wishlist = $result_wishlist->fetch_assoc()) {
                    $grand_total += $fetch_wishlist['price'];
            ?>

                    <form action="" method="post" class="box">
                        <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>">
                        <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_wishlist['name']; ?>">
                        <input type="hidden" name="price" value="<?= $fetch_wishlist['price']; ?>">
                        <input type="hidden" name="image" value="<?= $fetch_wishlist['image']; ?>">
                        <a href="quick_view.php?pid=<?= $fetch_wishlist['pid']; ?>" class="fas fa-eye"></a>
                        <img src="upload_img/<?= $fetch_wishlist['image']; ?>" alt="">
                        <div class="name"><?= $fetch_wishlist['name']; ?></div>
                        <div class="flex">
                            <div class="price">Rp<?= number_format($fetch_wishlist['price'], 0, ',', '.'); ?></div>
                            <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                        </div>
                        <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                        <input type="submit" value="delete item" onclick="return confirm('delete this from wishlist?');" class="delete-btn" name="delete">
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">your wishlist is empty</p>';
            }
            ?>
        </div>

        <div class="wishlist-total">
            <p>grand total: <span><?= 'Rp ' . number_format($grand_total, 0, ',', '.'); ?>,-</span></p>
            <a href="shop.php" class="option-btn">continue shopping</a>
            <?php if ($grand_total > 1) : ?>
                <a href="wishlist.php?delete_all" class="delete-btn">delete all item</a>
            <?php endif; ?>

        </div>

    </section>





    <?php include 'footer.php'; ?>

    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

</body>

</html>