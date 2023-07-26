<?php
include('koneksi.php');
session_start();

if (isset($_POST["submit"])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $user_type = 'user';

    // Enkripsi kata sandi
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'upload_img/';

    $select_users = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE email='$email'") or die('query failed');

    if (mysqli_num_rows($select_users) > 0) {
        $message[] = 'User/Email already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password does not match';
        } else {
            $upload_path = $image_folder . $image;
            $upload_extension = pathinfo($upload_path, PATHINFO_EXTENSION);
            $upload_file_name = pathinfo($upload_path, PATHINFO_FILENAME);
            $upload_file_name = $upload_file_name . '_' . time() . '.' . $upload_extension;
            $upload_destination = $image_folder . $upload_file_name;

            if (file_exists($upload_destination)) {
                $message[] = 'File with the same name already exists';
            } else {
                $insert = mysqli_query($conn, "INSERT INTO `tb_user`(name, email, password, user_type, image) VALUES('$name','$email','$hashed_password','$user_type','$upload_file_name')")
                    or die('query failed');

                if ($insert) {
                    if ($image_size > 2000000) {
                        $message[] = 'Image size is too large!';
                    } else {
                        move_uploaded_file($image_tmp_name, $upload_destination);
                        $message[] = 'Signup successful';
                        header("refresh:1; url=login.php");
                    }
                }
            }
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
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

    <title>SIGNUP</title>
</head>

<body>

    <?php
    if (isset($message)) {
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

    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="login">
            <i class="fa-solid fa-book" id="book"></i>
            <h2 id="txt">SignUp</h2>
            <span class="logo" id="logo">Bookshelf</span>
            <div class="inputBox">
                <input type="text" name="name" id="name" placeholder="Username">
            </div>
            <div class="inputBox">
                <input type="email" name="email" id="email" placeholder="Email">
            </div>
            <div class="inputPass">
                <input type="password" name="password" class="input__field" id="password" placeholder="Password">
                <span class="input__icon-wrapper">
                    <i class="input__icon ri-eye-off-line" id="eye"></i>
                </span>
            </div>
            <div class="inputPass">
                <input type="password" name="cpassword" class="input__field" id="cpassword" placeholder="Confirm Password">
                <span class="input__icon-wrapper">
                    <i class="input__icon ri-eye-off-line" id="eyes"></i>
                </span>
            </div>
            <div class="inputBox">
                <label>Profile Picture</label>
                <input type="file" name="image" id="image" accept="image/jpg, image/jpeg, image/png" required>
            </div>
            <div class="inputBox">
                <input type="submit" name="submit" value="SignUp" id="btn">
            </div>
            <div class="group">
                <a href="login.php">Already have account? SignIn</a>
            </div>
        </div>
    </form>
    <div class="colors">
        <span onclick="changeColor('#1dd1a1')" style="--clr:#1dd1a1;"></span>
        <span onclick="changeColor('#ff6b6b')" style="--clr:#ff6b6b;"></span>
        <span class="active" onclick="changeColor('#2e86de')" style="--clr:#2e86de;"></span>
        <span onclick="changeColor('#f368e0')" style="--clr:#f368e0;"></span>
        <span onclick="changeColor('#ff9f43')" style="--clr:#ff9f43;"></span>
    </div>

    <!-- custom admin js file link -->
    <script src="js/login.js"></script>
</body>

</html>