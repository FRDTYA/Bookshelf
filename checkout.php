<?php

include('koneksi.php');
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
};

function generateRandomString($minLength = 24, $maxLength = 50)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    $length = rand($minLength, $maxLength);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$order_id = generateRandomString();


if (isset($_POST['order'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $sorder = mysqli_real_escape_string($conn, $_POST['sorder']);
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = '-';
    $address = '-';
    $city = '';
    $province = '';
    $postal_code = '';

    if ($sorder == 'delivery') {
        $method = mysqli_real_escape_string($conn, $_POST['method']);
        $address = mysqli_real_escape_string($conn, $_POST['flat'] . ', ' . $_POST['city'] .  ', ' . $_POST['state'] . ' - ' . $_POST['pin_code']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $province = mysqli_real_escape_string($conn, $_POST['state']);
        $postal_code = mysqli_real_escape_string($conn, $_POST['pin_code']);
    }

    // Upload gambar
    $imageName = "";
    if (isset($_FILES['image']) && $_FILES['image']['name']) {
        $targetDir = "upload_img/"; // Direktori tujuan penyimpanan gambar
        $imageName = $_FILES['image']['name']; // Nama gambar yang diunggah
        $targetFilePath = $targetDir . $imageName; // Path lengkap gambar

        // Pindahkan gambar ke direktori upload_img
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath);
    }

    $cart_total = 0;
    $cart_products[] = '';

    $cart_query = mysqli_query($conn, "SELECT * FROM `tb_cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['price'] . ' x ' . $cart_item['quantity'] . ') - ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `tb_orders` WHERE name = '$name' AND sistem_order = '$sorder' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'your cart is empty';
    } else {
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'order already placed!';
        } else {
            mysqli_query($conn, "INSERT INTO `tb_orders` (user_id, name, order_id, sistem_order, number, email, method, address, total_products, total_price, review_image)
            VALUES ('$user_id', '$name', '$order_id', '$sorder', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$imageName')")
                or die('query failed');
            $message[] = 'order placed successfully!';
            mysqli_query($conn, "DELETE FROM `tb_cart` WHERE user_id = '$user_id'") or die('query failed');
        }
    }
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
    <title>checkout</title>
</head>

<body>

    <?php include 'header.php'; ?>

    <section class="checkout-orders">

        <form action="" method="POST" enctype="multipart/form-data">

            <h3>your orders</h3>

            <div class="display-orders">
                <?php
                $grand_total = 0;
                $select_cart = mysqli_query($conn, "SELECT * FROM `tb_cart` WHERE user_id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                        $grand_total += $total_price;
                ?>
                        <p><?php echo $fetch_cart['name']; ?> <span>(<?php echo 'Rp ' . number_format($fetch_cart['price'], 0, ',', '.') . '/-' . ' x ' . $fetch_cart['quantity']; ?>)</span></p>
                <?php
                    }
                } else {
                    echo '<p class="empty">your cart is empty</p>';
                }
                ?>
                <div class="grand-total"> total checkout : <span><?php echo 'Rp ' . number_format($grand_total, 0, ',', '.') . '/-' ?></span> </div>
            </div>

            <h3>place your orders</h3>

            <div class="flex">
                <div class="inputBox" id="sorder">
                    <span>system order :</span>
                    <select name="sorder" class="box" required onchange="toggleInputs(this.value)">
                        <option value="delivery">delivery</option>
                        <option value="take away">take away</option>
                    </select>
                </div>
                <input type="hidden" name="order_id" maxlength="50" value="<?php echo $order_id; ?>">
                <?php
                $query = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = $user_id");
                while ($data_orders = mysqli_fetch_assoc($query)) {
                ?>
                    <div class="inputBox" id="nameBox">
                        <span>your name :</span>
                        <input type="text" name="name" value="<?= $data_orders['name']; ?>" placeholder="enter your name" class="box" required>
                    </div>

                    <div class="inputBox" id="numberBox">
                        <span>your number :</span>
                        <input type="number" name="number" placeholder="enter your number" class="box" min="0" max="99999999999999" onkeypress="if(this.value.length == 15) return false;" required>
                    </div>

                    <div class="inputBox" id="emailBox">
                        <span>your email :</span>
                        <input type="email" name="email" value="<?= $data_orders['email']; ?>" placeholder="enter your email" class="box" required>
                    </div>
                <?php
                }
                ?>

                <div class="inputBox" id="methodBox">
                    <span>payment method :</span>
                    <select name="method" class="box" required>
                        <option value="cash on delivery">cash on delivery</option>
                        <option value="credit card">credit card</option>
                        <option value="paytm">paytm</option>
                        <option value="paypal">paypal</option>
                    </select>
                </div>

                <?php
                $query_address = mysqli_query($conn, "SELECT * FROM tb_address WHERE user_id = $user_id");
                while ($data_address = mysqli_fetch_assoc($query_address)) {
                ?>
                <div class="inputBox" id="addressBox">
                    <span>address :</span>
                    <input type="text" name="flat" value="<?= $data_address['address']; ?>" placeholder="cth : jln KH Hasyim" class="box" maxlength="50" required>
                </div>

                <div class="inputBox" id="cityBox">
                    <span>city :</span>
                    <input type="text" name="city" value="<?= $data_address['city']; ?>" placeholder="cth : Jakarta" class="box" maxlength="50" required>
                </div>

                <div class="inputBox" id="stateBox">
                    <span>province :</span>
                    <input type="text" name="state" value="<?= $data_address['province']; ?>" placeholder="cth : Ibu Kota Jakarta" class="box" maxlength="50" required>
                </div>

                <div class="inputBox" id="pinCodeBox">
                    <span>postal code :</span>
                    <input type="number" min="0" name="pin_code" value="<?= $data_address['postal_code']; ?>" placeholder="cth : 10150" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
                </div>
                <?php
                }
                ?>

                <div class="inputBox" id="imgCodeBox">
                    <span>proof transfer :</span>
                    <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
                </div>
            </div>

            <input type="submit" name="order" class="btn" value="place order">
        </form>


        <?php include 'footer.php'; ?>

        <!-- custom admin js file link -->
        <script src="js/admin_script.js"></script>

        <script>
            function toggleInputs(value) {
                var nameBox = document.getElementById("nameBox");
                var numberBox = document.getElementById("numberBox");
                var emailBox = document.getElementById("emailBox");
                var methodBox = document.getElementById("methodBox");
                var addressBox = document.getElementById("addressBox");
                var cityBox = document.getElementById("cityBox");
                var stateBox = document.getElementById("stateBox");
                var pinCodeBox = document.getElementById("pinCodeBox");
                var imgCodeBox = document.getElementById("imgCodeBox");

                if (value === "take away") {
                    nameBox.style.display = "block";
                    numberBox.style.display = "block";
                    emailBox.style.display = "block";
                    methodBox.style.display = "none";
                    addressBox.style.display = "none";
                    cityBox.style.display = "none";
                    stateBox.style.display = "none";
                    pinCodeBox.style.display = "none";
                    imgCodeBox.style.display = "none";
                    nameBox.querySelector("input").setAttribute("required", "");
                    numberBox.querySelector("input").setAttribute("required", "");
                    emailBox.querySelector("input").setAttribute("required", "");
                    methodBox.querySelector("select").removeAttribute("required");
                    addressBox.querySelector("input").removeAttribute("required");
                    cityBox.querySelector("input").removeAttribute("required");
                    stateBox.querySelector("input").removeAttribute("required");
                    pinCodeBox.querySelector("input").removeAttribute("required");
                    imgCodeBox.querySelector("input").removeAttribute("required");
                } else if (value === "delivery") {
                    nameBox.style.display = "block";
                    numberBox.style.display = "block";
                    emailBox.style.display = "block";
                    methodBox.style.display = "block";
                    addressBox.style.display = "block";
                    cityBox.style.display = "block";
                    stateBox.style.display = "block";
                    pinCodeBox.style.display = "block";
                    imgCodeBox.style.display = "block";
                    nameBox.querySelector("input").setAttribute("required", "");
                    numberBox.querySelector("input").setAttribute("required", "");
                    emailBox.querySelector("input").setAttribute("required", "");
                    methodBox.querySelector("select").setAttribute("required", "");
                    addressBox.querySelector("input").setAttribute("required", "");
                    cityBox.querySelector("input").setAttribute("required", "");
                    stateBox.querySelector("input").setAttribute("required", "");
                    pinCodeBox.querySelector("input").setAttribute("required", "");
                    imgCodeBox.querySelector("input").setAttribute("required", "");
                }
            }
        </script>

</body>

</html>