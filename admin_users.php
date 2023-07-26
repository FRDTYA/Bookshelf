<?php

include('koneksi.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
};

// Mengecek apakah user telah memilih opsi filter
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    $select_users = $conn->prepare("SELECT * FROM `tb_user` WHERE user_type = ?");
    $select_users->bind_param("s", $filter);
} else {
    $select_users = $conn->prepare("SELECT * FROM `tb_user`");
};

$select_users->execute();
$result_users = $select_users->get_result();

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_users = $conn->prepare("DELETE FROM `tb_user` WHERE id = ?");
    $delete_users->bind_param("i", $delete_id);
    $delete_users->execute();
    header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- custom admin css file link -->
    <link rel="stylesheet" href="css/admin_style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".box-container .box").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

</head>

<body>

    <?php include 'admin_header.php';  ?>

    <section class="user-accounts">
        <h1 class="title">user accounts</h1>

        <div class="search-filter-container">
            <div class="search-box">
                <input type="text" id="search" placeholder="Search Users">
                <i class="fas fa-search"></i>
            </div>
            <form method="post" class="filter-form">
                <select name="filter" class="filter">
                    <option value="" selected disabled>All Users</option>
                    <option value="user">Normal User</option>
                    <option value="admin">Admin User</option>
                </select>
                <!-- <button type="submit" class="option-btn">Filter</button> -->
            </form>
        </div>


        <div class="box-container">

            <?php
            while ($fetch_users = $result_users->fetch_assoc()) {
            ?>
                <div class="box" style="<?php if ($fetch_users['id'] == $admin_id) {echo 'display:none';}; ?>">
                    <img src="upload_img/<?= $fetch_users['image']; ?>" alt="">
                    <p> user id : <span><?= $fetch_users['id']; ?></span></p>
                    <p> username : <span><?= $fetch_users['name']; ?></span></p>
                    <p> email : <span><?= $fetch_users['email']; ?></span></p>
                    <p> user type : <span style=" color:<?php if ($fetch_users['user_type'] == 'admin') {echo 'orange';}; ?>"><?= $fetch_users['user_type']; ?></span></p>
                    <a href="admin_users.php?delete=<?= $fetch_users['id']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete</a>
                </div>
            <?php
            }
            ?>
        </div>
    </section>

    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

    <script>
        const filterSelect = document.querySelector('.filter');
        filterSelect.addEventListener('change', () => {
            const filterValue = filterSelect.value;
            window.location.href = `admin_users.php?filter=${filterValue}`;
        });
    </script>



</body>

</html>