<?php

include('koneksi.php');
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

if (isset($_POST['update_profile'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $update_profile = $conn->prepare("UPDATE `tb_user` SET name = ?, email = ? WHERE id = ?");
    $update_profile->execute([$name, $email, $user_id]);

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'upload_img/';
    $old_image = $_POST['old_image'];

    if (!empty($image)) {
        $upload_path = $image_folder . $image;
        $upload_extension = pathinfo($upload_path, PATHINFO_EXTENSION);
        $upload_file_name = pathinfo($upload_path, PATHINFO_FILENAME);
        $upload_file_name = $upload_file_name . '_' . time() . '.' . $upload_extension;
        $upload_destination = $image_folder . $upload_file_name;

        if (file_exists($upload_destination)) {
            $message[] = 'File with the same name already exists';
        } else {
            $update_image = $conn->prepare("UPDATE `tb_user` SET image = ? WHERE id = ?");
            $update_image->execute([$upload_file_name, $user_id]);
            if ($update_image) {
                move_uploaded_file($image_tmp_name, $upload_destination);
                unlink('upload_img/' . $old_image);
                $message[] = 'Image updated!';
            }
        }
    }

    $new_pass = $_POST['new_pass'];
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $confirm_pass = $_POST['confirm_pass'];
    $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

    if (!empty($new_pass) || !empty($confirm_pass)) {
        if ($new_pass != $confirm_pass) {
            $message[] = 'Confirm password not matched!';
        } else {
            // Encrypt the new password
            $encrypted_pass = password_hash($confirm_pass, PASSWORD_DEFAULT);

            $update_pass_query = $conn->prepare("UPDATE `tb_user` SET password = ? WHERE id = ?");
            $update_pass_query->execute([$encrypted_pass, $user_id]);
            $message[] = 'Password updated successfully!';
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
    <title>update user profile</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- custom admin css file link -->
    <link rel="stylesheet" href="css/components.css">

</head>

<body>

    <?php include 'header.php';  ?>

    <!-- update admin profile section start -->
    <section class="update-profile">

        <h1 class="title">update profile</h1>



        <form action="" method="post" enctype="multipart/form-data">
            <img src="upload_img/<?= $fetch_profile['image']; ?>" alt="">
            <div class="flex">
                <div class="inputBox">
                    <span>username :</span>
                    <input type="text" class="box" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="update username" required>
                    <span>email :</span>
                    <input type="email" class="box" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="update email" required>
                    <span>update picture :</span>
                    <input type="file" class="box" name="image" accept="image/jpg, 
                    image/jpeg, image/png">
                    <input type="hidden" name="old_image" value="<?= $fetch_profile['image']; ?>">
                </div>
                <div class="inputBox">                    
                    <span>new password :</span>
                    <input type="password" class="box" name="new_pass" placeholder="enter new password">
                    <span>confirm password :</span>
                    <input type="password" class="box" name="confirm_pass" placeholder="confirm new password">
                </div>
            </div>
            <div class="flex-btn">
                <input type="submit" class="btn" value="update profile" name="update_profile">
                <a href="index.php" class="option-btn">go back</a>
            </div>
        </form>

    </section>


    <!-- update admin profile section ends -->




    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

</body>

</html>