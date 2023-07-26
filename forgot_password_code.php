<?php
include('koneksi.php');
session_start();

if (isset($_POST["send_code"])) {
    $email = $_SESSION['email'];

    // Cek apakah email terdaftar
    $select_users = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE email='$email'") or die('query failed');
    if (mysqli_num_rows($select_users) > 0) {
        $user = mysqli_fetch_assoc($select_users);
        $user_id = $user['id'];

        // Generate code acak
        $code = generateRandomCode(4, 6);

        // Simpan code ke dalam database
        $update_query = mysqli_query($conn, "UPDATE `tb_user` SET code='$code' WHERE id='$user_id'");
        if ($update_query) {
            $_SESSION['code'] = $code; // Simpan code dalam sesi

            $message[] = 'Your verification code is: '. $code;            
        } else {
            // Tambahkan pesan gagal
            $message[] = 'Failed to save code';
        }
    } else {
        // Tambahkan pesan email tidak terdaftar
        $message[] = 'Email not Registered';
    }
}

if (isset($_POST["submit"])) {
    $input_code = $_POST["code"];
    $stored_code = $_SESSION['code'];

    if ($input_code == $stored_code) {
        $message[] = 'Code Correct Wait 2 Second';
        header("refresh:2; url=forgot_password.php"); // Redirect ke halaman forgot_password_code.php setelah 2 detik        
    } else {
        $message[] = 'Code Incorrect';
    }
}

// Fungsi untuk menghasilkan code acak
function generateRandomCode($min_length, $max_length)
{
    $length = rand($min_length, $max_length);
    $characters = '0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
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
                <input type="text" name="code" id="code" placeholder="Input Code" <?php if (!isset($_POST["send_code"])) { echo 'style="display: none;"'; } ?>>
            </div>
            <div class="inputBox">
                <input type="submit" name="send_code" value="Send Code" id="btn" <?php if (isset($_POST["send_code"])) { echo 'style="display: none;"'; } ?>>
            </div>
            <div class="inputBox">
                <input type="submit" name="submit" value="Press This" id="btn" <?php if (!isset($_POST["send_code"])) { echo 'style="display: none;"'; } ?>>
            </div>
        </div>
    </form>

    <script src="js/login.js"></script>
</body>

</html>
