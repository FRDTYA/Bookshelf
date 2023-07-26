<?php

include('koneksi.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
};

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);
    $writer = $_POST['writer'];
    $writer = filter_var($writer, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'upload_img/' . $image;

    $stmt = $conn->prepare("SELECT name FROM tb_products WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message[] = 'product name already exist!';
    } else {
        $insert_products = $conn->prepare("INSERT INTO tb_products (name, writer, category, details, price, image) VALUES(?,?,?,?,?,?)");
        $insert_products->bind_param("ssssss", $name, $writer, $category, $details, $price, $image);
        $insert_products->execute();

        if ($insert_products) {
            if ($image_size > 2000000) {
                $message[] = 'image size too large!';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'new product added!';
            }
        }
    }
};


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `tb_products`
    WHERE id = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('upload_img/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `tb_products` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_products.php');
}

if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_writer = $_POST['update_writer'];
    $update_category = $_POST['update_category'];
    $update_details = $_POST['update_details'];
    $update_price = $_POST['update_price'];
    $message = array(); // inisialisasi variabel $message sebagai array

    mysqli_query($conn, "UPDATE `tb_products` SET name = '$update_name', writer = '$update_writer', 
    category = '$update_category', details = '$update_details', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'upload_img/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'image file size too large';
        } else {
            mysqli_query($conn, "UPDATE `tb_products` SET image = '$update_image'
            WHERE id = '$update_p_id'") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('upload_img/' . $update_old_image);
        }
    }

    $message[] = 'Product Updated';

    header('location:admin_products.php'); // mengirim variabel $message sebagai parameter URL
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>

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

    <!-- product CRUD section starts -->

    <section class="add-products">

        <h1 class="title">shop products</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <h3>add product</h3>
            <div class="flex">
                <input type="text" name="name" id="name" class="box" placeholder="enter product name" required>
                <div class="inputBox">
                    <input type="text" name="writer" class="box" placeholder="enter writer's name" required>
                    <select name="category" class="box" required>
                        <option value="" selected disabled>select category</option>
                        <option value="motivasi">motivasi</option>
                        <option value="agama">agama</option>
                        <option value="sejarah">sejarah</option>
                        <option value="pendidikan">pendidikan</option>
                        <option value="matematika">matematika</option>
                        <option value="sains">sains</option>
                        <option value="buku masakan">buku masakan</option>
                        <option value="finansial">finansial</option>
                        <option value="romance">romance</option>
                        <option value="komik">komik</option>
                    </select>
                </div>
                <div class="inputBox">
                    <input type="number" id="price" min="0" name="price" class="box" placeholder="enter product price" required>
                    <!-- nama file foto kecil semua contoh = seribu_wajah_ayah -->
                    <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
                </div>
            </div>
            <textarea name="details" class="box" placeholder="enter product details" required cols="30" rows="10"></textarea>
            <input type="submit" value="add product" name="add_product" class="btn">
        </form>

    </section>

    <!-- product CRUD section ends -->
    <!-- show products start-->
    <section class="show-products">
        <h1 class="title">products added</h1>
        <div class="search-box">
            <input type="text" id="search" placeholder="Search Product">
            <i class="fas fa-search"></i>
        </div>
        <div class="box-container">
            
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `tb_products`")
                or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
                    <div class="box">
                        <img src="upload_img/<?php echo $fetch_products['image']; ?>" alt="">
                        <div class="name"><?php echo $fetch_products['name']; ?></div>
                        <div class="writer"><?php echo $fetch_products['writer']; ?></div>
                        <div id="price" class="price"><?php echo 'Rp ' . number_format($fetch_products['price'], 0, ',', '.'); ?></div>
                        <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
                        <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?')">delete</a>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no product added yet!</p>';
            }
            ?>
        </div>
    </section>

    <section class="edit-product-form">
        <?php
        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM `tb_products` WHERE
            id = '$update_id'") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {
        ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                        <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">

                        <img src="upload_img/<?php echo $fetch_update['image']; ?>" alt="">
                        <div class="flex">
                            <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">

                            <div class="inputBox">
                                <input type="text" name="update_writer" value="<?php echo $fetch_update['writer']; ?>" class="box" required placeholder="enter writer's name">
                                <select name="update_category" class="box" required>
                                    <option selected><?php echo $fetch_update['category']; ?></option>
                                    <option value="motivasi">motivasi</option>
                                    <option value="agama">agama</option>
                                    <option value="sejarah">sejarah</option>
                                    <option value="pendidikan">pendidikan</option>
                                    <option value="matematika">matematika</option>
                                    <option value="sains">sains</option>
                                    <option value="buku masakan">buku masakan</option>
                                    <option value="finansial">finansial</option>
                                    <option value="romance">romance</option>
                                    <option value="komik">komik</option>
                                </select>
                            </div>
                            <div class="inputBox">
                                <input type="number" id="price" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
                                <input type="file" class="box" name="update_image" accept="image/jpg, 
                                image/jpeg, image/png">
                            </div>
                        </div>
                        <textarea name="update_details" class="box" placeholder="enter product details" required cols="30" rows="10"><?php echo $fetch_update['details']; ?></textarea>
                        <input type="submit" value="update" name="update_product" class="btn">
                        <input type="reset" value="cancel" id="close-update" class="option-btn">
                    </form>
        <?php
                }
            }
        } else {
            echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
        }

        ?>
    </section>
    <!-- show products end-->




    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

</body>

</html>