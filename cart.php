<?php

include('koneksi.php');
session_start();

$user_id = $_SESSION['user_id'];

// Cek apakah alamat sudah ditambahkan
$check_address = $conn->prepare("SELECT * FROM tb_address WHERE user_id = ?");
$check_address->bind_param("i", $user_id);
$check_address->execute();
$check_address_result = $check_address->get_result();
$address_exists = $check_address_result->num_rows;

if ($address_exists == 0) {
    // Redirect ke halaman tambah alamat jika alamat belum ditambahkan
    header('location:user_address.php');
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
};

if (isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];
    $delete_cart_item = $conn->prepare("DELETE FROM `tb_cart` WHERE id = ?");
    $delete_cart_item->execute([$cart_id]);
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `tb_cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['qty'];
    $update_qty = $conn->prepare("UPDATE `tb_cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$qty, $cart_id]);
    $message[] = 'Cart quantity updated!';
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
    <title>shopping cart</title>
</head>

<style>
    .hide {
        display: none;
    }
</style>

<body>

    <?php include 'header.php'; ?>

    <section class="products shopping-cart">
        <h3 class="heading">shopping cart</h3>
        <div class="box-container">
            <?php
            $grand_total = 0;
            $select_cart = mysqli_query($conn, "SELECT * FROM `tb_cart` WHERE user_id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                    $grand_total += $sub_total;
            ?>
                    <form action="" method="post" class="box">
                        <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                        <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                        <img src="upload_img/<?= $fetch_cart['image']; ?>" alt="">
                        <div class="name"><?= $fetch_cart['name']; ?></div>
                        <div class="flex">
                            <div class="price"><?= 'Rp ' . number_format($fetch_cart['price'], 0, ',', '.'); ?></div>
                            <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>" onchange="updateSubTotal(this, <?= $fetch_cart['price']; ?>, <?= $fetch_cart['id']; ?>)">
                            <button type="submit" class="fas fa-edit" name="update_qty"></button>
                        </div>
                        <div class="sub-total"> sub total : <span id="sub-total-<?= $fetch_cart['id']; ?>"><?= 'Rp ' . number_format($sub_total, 0, ',', '.'); ?>,-</span> </div>
                        <input type="submit" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete">
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">your cart is empty</p>';
            }
            ?>

        </div>

        <div class="cart-total">
            <p>total checkout : <span id="grand-total"><?= 'Rp ' . number_format($grand_total, 0, ',', '.'); ?></span></p>
            <a href="shop.php" class="option-btn">continue shopping</a>
            <a href="cart.php?delete_all" class="delete-btn <?php echo (mysqli_num_rows($select_cart) > 0) ? '' : 'hide'; ?>" onclick="return confirm('delete all from cart?');">delete all item</a>
            <a href="<?php echo ($address_exists > 0) ? 'checkout.php' : 'user_address.php'; ?>" class="btn <?php echo (mysqli_num_rows($select_cart) > 0) ? '' : 'hide'; ?>">checkout</a>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

    <script>
        function updateSubTotal(input, price, cartId) {
            var quantity = input.value;
            var subTotal = price * quantity;
            var subTotalElement = document.getElementById('sub-total-' + cartId);
            subTotalElement.innerHTML = 'Rp ' + formatNumber(subTotal) + ',-';

            var grandTotalElement = document.getElementById('grand-total');
            var currentGrandTotal = parseInt(grandTotalElement.innerHTML.replace(/[^\d]/g, ''));
            var newGrandTotal = currentGrandTotal - (price * input.defaultValue) + subTotal;
            grandTotalElement.innerHTML = 'Rp ' + formatNumber(newGrandTotal) + ',-';

            input.defaultValue = quantity;

            // Kirim data ke server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_cart_quantity.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('cart_id=' + cartId + '&qty=' + quantity);
        }

        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    </script>

</body>

</html>
