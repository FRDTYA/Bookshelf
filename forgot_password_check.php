<?php
include('koneksi.php');
session_start();

if (isset($_POST["submit"])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Enkripsi kata sandi
    // $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
    $select_users = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE email='$email'") or die('query failed');

    // Cek apakah email terdaftar
    if (mysqli_num_rows($select_users) > 0) {
        $message[] = 'Your Email is Registered';
        $_SESSION['email'] = $email; // Simpan email dalam sesi
        header("refresh:1; url=forgot_password_code.php"); // Redirect ke halaman forgot_password_code.php setelah 2 detik
    } else {
        $message[] = 'Email not Registered';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/forgot_password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

    <title>Forgot Password</title>
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
            <h2 id="txt">Forgot Password</h2>
            <span class="logo" id="logo">Bookshelf</span>
            <div class="inputBox">
                <input type="email" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="inputBox">
                <input type="submit" name="submit" value="Check" id="btn">
            </div>            
        </div>
    </form>
    <!-- <div class="colors">
        <span onclick="changeColor('#1dd1a1')" style="--clr:#1dd1a1;"></span>
        <span onclick="changeColor('#ff6b6b')" style="--clr:#ff6b6b;"></span>
        <span onclick="changeColor('#2e86de')" style="--clr:#2e86de;"></span>
        <span class="active" onclick="changeColor('#f368e0')" style="--clr:#f368e0;"></span>
        <span onclick="changeColor('#ff9f43')" style="--clr:#ff9f43;"></span>
    </div> -->

    <!-- custom admin js file link -->
    <script src="js/login.js"></script>
</body>

</html>