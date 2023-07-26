<?php

include('koneksi.php');
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

$fetch_address_query = $conn->prepare("SELECT address, city, province, postal_code FROM tb_address WHERE user_id = ?");
$fetch_address_query->bind_param("i", $user_id);
$fetch_address_query->execute();
$fetch_address_result = $fetch_address_query->get_result();
$fetch_address = $fetch_address_result->fetch_assoc();

if (isset($_POST['update_address'])) {

    $address = $_POST['address'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $city = $_POST['city'];
    $city = filter_var($city, FILTER_SANITIZE_STRING);
    $province = $_POST['province'];
    $province = filter_var($province, FILTER_SANITIZE_STRING);
    $postal_code = $_POST['postal_code'];
    $postal_code = filter_var($postal_code, FILTER_SANITIZE_STRING);

    $update_address = $conn->prepare("UPDATE `tb_address` SET address = ?, city = ?, province = ?, postal_code = ? WHERE user_id = ?");
    $update_address->bind_param("ssssi", $address, $city, $province, $postal_code, $user_id);
    $update_address->execute();

    // if ($update_address) {
    //     $message[] = 'Address Updated';
    // }

    if ($update_address) {
        $message[] = 'Address Added';
        header("Refresh:2");
    }
}

if (isset($_POST['add_address'])) {

    $check_address_query = $conn->prepare("SELECT * FROM tb_address WHERE user_id = ?");
    $check_address_query->bind_param("i", $user_id);
    $check_address_query->execute();
    $check_address_result = $check_address_query->get_result();

    if ($check_address_result->num_rows > 0) {
        $message[] = 'You have already added an address';
    } else {
        $address = $_POST['address'];
        $address = filter_var($address, FILTER_SANITIZE_STRING);
        $city = $_POST['city'];
        $city = filter_var($city, FILTER_SANITIZE_STRING);
        $province = $_POST['province'];
        $province = filter_var($province, FILTER_SANITIZE_STRING);
        $postal_code = $_POST['postal_code'];
        $postal_code = filter_var($postal_code, FILTER_SANITIZE_STRING);
        $namaNya = $_POST['name'];
        $namaNya = filter_var($namaNya, FILTER_SANITIZE_STRING);

        $insert_address = $conn->prepare("INSERT INTO `tb_address` (address, city, province, postal_code, user_id, name) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_address->bind_param("ssssis", $address, $city, $province, $postal_code, $user_id, $namaNya);
        $insert_address->execute();

        if ($insert_address) {
            $message[] = 'Address Added';
            $message[] = 'You Can Checkout Now';
            header("Refresh:2");
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
    <title>add address</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- custom admin css file link -->
    <link rel="stylesheet" href="css/components.css">

</head>

<body>

    <?php include 'header.php';  ?>

    <!-- update address section start -->
    <section class="update-address">
        
    <?php if ($fetch_address) : ?>
            <h1 class="title">address default</h1>            
        <?php else : ?>
            <h1 class="title">add address first</h1>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <img src="upload_img/<?= $fetch_profile['image']; ?>" alt="">
            <p class="nama"><?= $fetch_profile['name']; ?></p>

            <!-- <input type="hidden" name="user_id" value="<?= $fetch_profile['user_id']; ?>"> -->
            <input type="hidden" name="name" value="<?= $fetch_profile['name']; ?>">
            <div class="flex">
                <div class="inputBox">
                    <span>address :</span>
                    <?php if (!$fetch_address) : ?>
                        <input type="text" class="box" name="address" placeholder="add address" required>
                    <?php else : ?>
                        <input type="text" class="box" name="address" value="<?= $fetch_address['address']; ?>" placeholder="add address" required>
                    <?php endif; ?>

                    <span>city :</span>
                    <?php if (!$fetch_address) : ?>
                        <input type="text" class="box" name="city" placeholder="add city" required>
                    <?php else : ?>
                        <input type="text" class="box" name="city" value="<?= $fetch_address['city']; ?>" placeholder="add city" required>
                    <?php endif; ?>
                </div>
                <div class="inputBox">
                    <span>province :</span>
                    <?php if (!$fetch_address) : ?>
                        <input type="text" class="box" name="province" placeholder="add province" required>
                    <?php else : ?>
                        <input type="text" class="box" name="province" value="<?= $fetch_address['province']; ?>" placeholder="add province" required>
                    <?php endif; ?>

                    <span>postal code :</span>
                    <?php if (!$fetch_address) : ?>
                        <input type="text" class="box" name="postal_code" placeholder="add postal code" required>
                    <?php else : ?>
                        <input type="text" class="box" name="postal_code" value="<?= $fetch_address['postal_code']; ?>" placeholder="add postal code" required>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex-btn">
                <?php if (!$fetch_address) : ?>
                    <input type="submit" class="btn" value="add address" name="add_address">
                <?php endif; ?>
                <?php if ($fetch_address) : ?>
                    <input type="submit" class="option-btn" value="update address" name="update_address">
                <?php endif; ?>
                <a href="index.php" class="delete-btn">go back</a>
            </div>
        </form>

    </section>


    <!-- update address section ends -->

    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

</body>

</html>
