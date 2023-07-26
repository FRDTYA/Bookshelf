<?php
include('koneksi.php');
session_start();

if (isset($_POST["submit"])) {
    $email = $_SESSION['email'];
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

            $update_pass_query = $conn->prepare("UPDATE `tb_user` SET password = ? WHERE email = ?");
            $update_pass_query->execute([$encrypted_pass, $email]);
            $message[] = 'New Password Successfully!';
            header("refresh:1; url=login.php");
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
    <link rel="stylesheet" href="css/forgot_password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

    <title>Forgot Password</title>
</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
        <div class="message">
            <span>' . $message . '</span>
             <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
        }
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="login">
            <i class="fa-solid fa-book" id="book"></i>
            <h2 id="txt">ForgotPassword</h2>
            <span class="logo" id="logo">Bookshelf</span>            
            <div class="inputPass">
                <input type="password" name="new_pass" class="input__field" id="password" placeholder="New Password">
                <span class="input__icon-wrapper">
                    <i class="input__icon ri-eye-off-line" id="eye"></i>
                </span>
            </div>
            <div class="inputPass">
                <input type="password" name="confirm_pass" class="input__field" id="cpassword" placeholder="Confirm Password">
                <span class="input__icon-wrapper">
                    <i class="input__icon ri-eye-off-line" id="eyes"></i>
                </span>
            </div>
            <div class="inputBox">
                <input type="submit" name="submit" value="Submit" id="btn">
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