<?php
include('koneksi.php');
session_start();

if (isset($_POST["submit"])) {
    $username_email = mysqli_real_escape_string($conn, $_POST['username_email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    $select_users = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE (email='$username_email' OR name='$username_email')") or die('Query failed');

    if (mysqli_num_rows($select_users) > 0) {

        $row = mysqli_fetch_assoc($select_users);
        $hashed_password = $row['password'];

        if (password_verify($pass, $hashed_password)) {

            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];
                header('location:admin_page.php');
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                header('location:index.php');
            }

        } else {
            echo "<script>alert('Email/Username atau Password salah');window.location='login.php'</script>";
        }
    } else {
        echo "<script>alert('Email/Username atau Password salah');window.location='login.php'</script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="css/logsty.css">
    <title>Bookshelf</title>
</head>

<body>
    <form action="" method="post" autocomplete="off">
        <div class="login">
            <i class="fa-solid fa-book" id="book"></i>
            <h2 id="txt">Login</h2>
            <span class="logo" id="logo">Bookshelf</span>
            <div class="inputBox">
                <input type="text" name="username_email" id="username_email" placeholder="Email or Username">
            </div>
            <div class="inputPass">
                <!-- <div class="inputBox"> -->
                <input type="password" name="password" class="input__field" id="password" placeholder="Password">
                <span class="input__icon-wrapper">
                    <i class="input__icon ri-eye-off-line" id="eye"></i>
                </span>
                <!-- </div> -->
            </div>
            <div class="inputBox">
                <input type="submit" name="submit" value="Login" id="btn">
            </div>
            <div class="group">
                <a href="forgot_password_check.php">Forgot Password</a>
                <a href="signup.php">Signup</a>
            </div>
        </div>
    </form>

    <div class="colors">
        <span onclick="changeColor('#1dd1a1')" style="--clr:#1dd1a1;"></span>
        <span onclick="changeColor('#ff6b6b')" style="--clr:#ff6b6b;"></span>
        <span onclick="changeColor('#2e86de')" style="--clr:#2e86de;"></span>
        <span onclick="changeColor('#f368e0')" style="--clr:#f368e0;"></span>
        <span onclick="changeColor('#ff9f43')" style="--clr:#ff9f43;"></span>
    </div>

    <!-- custom admin js file link -->
    <script src="js/login.js"></script>
</body>

</html>