<?php

include('koneksi.php');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

include 'wishlist_cart.php';
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
    <title>shop</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#search-bar").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#bukucon #bukubox").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

</head>

<body>

    <style>
        #deskripsi {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            /* Sesuaikan lebar sesuai kebutuhan */
        }
    </style>

    <?php include 'header.php'; ?>

    <section class="search-book">
        <div class="search-bar-container">
            <input type="text" id="search-bar" placeholder="Cari...">
            <select id="category-dropdown" onchange="filterProducts()">
                <option value="Filter" selected disabled>Filter</option>
                <option value="All">All</option>
                <option value="Motivasi">Motivasi</option>
                <option value="Romance">Romance</option>
                <option value="category3">Kategori 3</option>
            </select>
        </div>
    </section>



    <section class="products">
        <h1 class="heading">bookshelf</h1>
        <div class="box-container" id="bukucon">
            <?php
            $selected_category = "All"; // Default selected category is "All"
            if (isset($_GET['category'])) {
                $selected_category = $_GET['category'];
            }

            $select_products = $conn->prepare("SELECT id, name, writer, details, price, image, category FROM tb_products" . ($selected_category !== "All" ? " WHERE category = ?" : ""));

            if ($selected_category !== "All") {
                $select_products->bind_param("s", $selected_category);
            }

            $select_products->execute();
            $select_products->store_result();
            if ($select_products->num_rows > 0) {
                $select_products->bind_result($product_id, $product_name, $product_writer, $product_details, $product_price, $product_image, $product_category);
                while ($select_products->fetch()) {
            ?>
                    <form action="" method="post" class="box" id="bukubox">
                        <input type="hidden" name="pid" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="name" value="<?php echo $product_name; ?>">
                        <input type="hidden" name="price" value="<?php echo $product_price; ?>">
                        <input type="hidden" name="image" value="<?php echo $product_image; ?>">
                        <input type="hidden" class="category" value="<?php echo $product_category; ?>">
                        <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                        <a href="quick_view.php?pid=<?php echo $product_id; ?>" class="fas fa-eye"></a>
                        <img src="upload_img/<?php echo $product_image; ?>" alt="">
                        <div class="name"><?php echo $product_name; ?></div>
                        <div class="writer"><?php echo $product_writer; ?></div>
                        <div class="flex">
                            <div class="price"><span>Rp</span><?php echo number_format($product_price, 0, ',', '.'); ?></div>
                            <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                        </div>
                        <div class="writer" id="deskripsi"><?php echo $product_details; ?></div>
                        <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">no products found!</p>';
            }
            ?>
        </div>
    </section>








    <?php include 'footer.php'; ?>

    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

    <script>
        var deskripsi = document.getElementById('deskripsi');
        var kata = deskripsi.innerHTML.split(' ').slice(0, 10).join(' ');
        deskripsi.innerHTML = kata + '...';
    </script>

    <script>
        function filterProducts() {
            var category = document.getElementById("category-dropdown").value;
            var urlParams = new URLSearchParams(window.location.search);
            urlParams.set('category', category);
            window.location.search = urlParams.toString();
        }
    </script>

</body>

</html>