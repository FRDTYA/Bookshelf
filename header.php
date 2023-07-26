<?php
if (isset($message) && is_array($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . $msg . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}

?>


<header class="header">

    <div class="flex">

        <a href="admin_page.php" class="logo"><span>Book</span>shelf</a>
        <i class="fa-solid fa-book" id="book"></i>

        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="orders.php">Orders</a>
            <!-- <a href="about.php">About</a> -->
            <a href="contact.php">Contact</a>
        </nav>

        <?php if (isset($user_id) && $user_id != "") { ?>
            <div class="icons">
                <?php
                $count_wishlist_items = $conn->prepare("SELECT * FROM `tb_wishlist` WHERE user_id = ?");
                $count_wishlist_items->bind_param("i", $user_id);
                $count_wishlist_items->execute();
                $count_wishlist_items->store_result();
                $total_wishlist_counts = $count_wishlist_items->num_rows;

                $count_cart_items = $conn->prepare("SELECT * FROM `tb_cart` WHERE user_id = ?");
                $count_cart_items->bind_param("i", $user_id);
                $count_cart_items->execute();
                $count_cart_items->store_result();
                $total_cart_counts = $count_cart_items->num_rows;
                ?>
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="user-btn" class="fas fa-user"></div>
                <a href="wishlist.php" class="fas fa-heart" id="wishlist-btn"><span>(<?= $total_wishlist_counts; ?>)</span></a>
                <a href="cart.php" class="fas fa-shopping-cart" id="cart-btn"><span>(<?= $total_cart_counts; ?>)</span></a>

            </div>

            <div class="profile">
                <?php
                $sql = "SELECT * FROM `tb_user` WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $fetch_profile = mysqli_fetch_assoc($result);
                ?>
                <img src="upload_img/<?= $fetch_profile['image']; ?>" alt="">
                <p><?= $fetch_profile['name']; ?></p>
                <a href="user_profile_update.php" class="btn">update profile</a>
                <a href="user_address.php" class="option-btn">Add Address</a>
                <a href="logout.php" class="delete-btn">logout</a>
            </div>
        <?php } else { ?>
            <div class="flex-btn">
                <a href="login.php" class="login-btn">login</a>
                <a href="signup.php" class="signup-btn">register</a>
            </div>
        <?php } ?>

    </div>

</header>
