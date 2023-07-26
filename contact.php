<?php

include('koneksi.php');
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
};

if (isset($_POST['send'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);

    $select_message = $conn->prepare("SELECT * FROM `tb_message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
    $select_message->bind_param("ssss", $name, $email, $number, $msg);
    $select_message->execute();
    $select_message_result = $select_message->get_result();

    if ($select_message_result->num_rows > 0) {
        $message[] = 'already sent message!';
    } else {

        $insert_message = $conn->prepare("INSERT INTO `tb_message`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
        $insert_message->bind_param("issss", $user_id, $name, $email, $number, $msg);
        $insert_message->execute();

        $message[] = 'sent message successfully!';
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
    <title>contact</title>
</head>

<body>

    <?php include 'header.php'; ?>

    <section class="contact">

        <form action="" method="post">
            <h3>What Happened ?</h3>
            <?php
            $query = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = $user_id");
            while($data_contact = mysqli_fetch_assoc($query)){
            ?>
            <input type="text" name="name" value="<?= $data_contact['name']?>" placeholder="enter your name" required class="box"> 
            <input type="email" name="email" value="<?= $data_contact['email']?>" placeholder="enter your email" required class="box">
            <?php
            }
            ?>
            <input type="number" name="number" min="0" max="9999999999" placeholder="enter your number" required onkeypress="if(this.value.length == 10) return false;" class="box">
            <textarea name="msg" class="box" placeholder="enter your message" cols="30" rows="10"></textarea>
            <input type="submit" value="send message" name="send" class="btn">
        </form>

    </section>





    <?php include 'footer.php'; ?>

    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

</body>

</html>