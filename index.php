<?php
include('koneksi.php');
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
 }else{
    $user_id = '';
 };


// Lanjutkan dengan kode halaman yang diakses setelah login
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <!-- custom admin css file link -->
    <link rel="stylesheet" href="css/style.css">
    <title>Bookshelf</title>
</head>

<body>

    <?php include 'header.php'; ?>

    <section class="home">

        <div class="content">
            <div class="text">
                <h2>Welcome to Bookshelf</h2>
                <p>we are offering a special promotion for our loyal customers. Get 10% off your purchase when you buy 3 or more books from our store. Plus, sign up for our newsletter to stay up-to-date on the latest releases, author events, and exclusive promotions.
                    So why wait? Come visit us at Bookshelf and discover your next favorite book.</p>
                <a href="shop.php" class="shop-now">Shop Now</a>
            </div>
            <div class="image">
                <div class="swiper books-slider swiper-books">
                    <div class="swiper-wrapper">
                        <?php
                        // Query untuk mendapatkan gambar dengan kategori "motivasi"
                        $query = "SELECT * FROM tb_products WHERE category = 'motivasi' LIMIT 8";
                        $result = mysqli_query($conn, $query);

                        // Loop melalui hasil query
                        while ($row = mysqli_fetch_assoc($result)) {
                            $gambar = $row['image']; // Kolom gambar dalam tabel

                            // Tampilkan gambar dalam elemen <img>
                            echo '<div class="swiper-slide"><img src="upload_img/' . $gambar . '" alt="Gambar" style="margin: 10px;"></div>';
                        }
                        ?>
                    </div>
                </div>
                <img src="image/stand2.png" alt="Gambar">
            </div>
        </div>

    </section>

    <section class="infokeun">
        <div class="swiper kontex-slider">
            <div class="swiper-wrapper">
                <div id="time" class="swiper-slide"></div>
                <div id="greeting" class="swiper-slide"></div>
            </div>
        </div>
    </section>

    <section class="benefit">
        <h3 id="choose-us-heading">why choose us ?</h3>
        <div class="box_benefit">
            <img src="image/delivery-truck.png" alt="">
            <h2>Free Shipping</h2>
        </div>
        <div class="box_benefit">
            <img src="image/original2.png" alt="">
            <h2>100% Original</h2>
        </div>
        <div class="box_benefit">
            <img src="image/updated.png" alt="">
            <h2>Updated</h2>
        </div>
    </section>

    <section class="peta">
        <h3 class="store-title">bookshelf offline store</h3>
        <div class="maps">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63465.84333881395!2d106.79526460727168!3d-6.182226079786346!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f436b8c94b07%3A0x6ea6d5398b7c82f6!2sJakarta%20Pusat%2C%20Kota%20Jakarta%20Pusat%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sid!2sid!4v1684387764334!5m2!1sid!2sid" width="500" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <div class="store-info">
                <h2>Kota Jakarta Pusat <span>Jl. KH. Hasyim Ashari No.125, Cideng, Kecamatan</span> <span>Gambir, Kota Jakarta Pusat, Daerah Khusus</span> <span>Ibukota Jakarta 10150</span></h2>
                <button class="whatsapp-button">
                    <a href="https://wa.me/1234567890" target="_blank">
                        <img src="image/whatsapp2.png" alt="WhatsApp Logo">
                        WhatsApp
                    </a>
                </button>
            </div>
        </div>
    </section>

    <section class="offline_store">
        <h2 class="store-heading">Bookshelf Store</h2>
        <div class="foto_toko">
            <img src="image/bookshelf1.jpg" alt="Image 1">
            <img src="image/bookshelf2.jpg" alt="Image 2">
            <img src="image/bookshelf3.jpg" alt="Image 3">
            <img src="image/bookshelf4.jpg" alt="Image 4">
            <img src="image/bookshelf5.jpg" alt="Image 5">
            <img src="image/bookshelf6.jpg" alt="Image 6">
            <img src="image/bookshelf7.jpg" alt="Image 7">
            <img src="image/bookshelf8.jpg" alt="Image 8">
            <img src="image/bookshelf9.jpg" alt="Image 9">
        </div>
    </section>







    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- //script slider -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var booksSlider = new Swiper('.books-slider', {
                loop: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                autoplay: {
                    delay: 3000, // Waktu tampilan setiap slide dalam milidetik (ms)
                    disableOnInteraction: false, // Jangan menghentikan autoplay saat pengguna berinteraksi dengan slider
                },

            });

            var infokeunSlider = new Swiper('.kontex-slider', {
                loop: true,
                centeredSlides: true,
                slidesPerView: 1,
                autoplay: {
                    delay: 3000, // Waktu tampilan setiap slide dalam milidetik (ms)
                    disableOnInteraction: false, // Jangan menghentikan autoplay saat pengguna berinteraksi dengan slider
                },

            });
        });
    </script>


    <!-- //script greetings and time -->
    <script>
        function updateTime() {
            var options = {
                timeZone: 'Asia/Jakarta',
                hour12: false,
                hour: 'numeric',
                minute: '2-digit',
                second: '2-digit'
            };
            var currentDateTime = new Date().toLocaleString('id-ID', options);
            var timeString = currentDateTime.replace(':');
            document.getElementById('time').textContent = currentDateTime;

            // Memperbarui setiap detik
            setTimeout(updateTime, 1000);
        }

        function updateGreeting() {
            var currentHour = new Date().getHours();
            var greetings;
            if (currentHour >= 5 && currentHour < 12) {
                greetings = 'Good Morning Bookers';
            } else if (currentHour >= 12 && currentHour < 18) {
                greetings = 'Good Afternoon Bookers';
            } else {
                greetings = 'Good Evening Bookers';
            }
            document.getElementById('greeting').textContent = greetings;
        }

        updateTime();
        updateGreeting();
    </script>


    <!-- //script animate benefit -->
    <script>
        function isElementInViewport(el) {
            var rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        function handleScrollAnimation() {
            var boxBenefits = document.querySelectorAll('.box_benefit');
            boxBenefits.forEach(function(boxBenefit) {
                if (isElementInViewport(boxBenefit)) {
                    boxBenefit.classList.add('show');
                } else {
                    boxBenefit.classList.remove('show');
                }
            });

            var chooseUsHeading = document.getElementById('choose-us-heading');
            if (isElementInViewport(chooseUsHeading)) {
                chooseUsHeading.classList.add('show');
            } else {
                chooseUsHeading.classList.remove('show');
            }
        }

        window.addEventListener('scroll', handleScrollAnimation);
        handleScrollAnimation();
    </script>


    <!-- //script animate peta -->
    <script>
        window.addEventListener('scroll', animateElements);

        function animateElements() {
            var petaSection = document.querySelector('.peta');
            var iframeElement = document.querySelector('.maps iframe');
            var storeInfoElement = document.querySelector('.store-info');

            var petaSectionPosition = petaSection.getBoundingClientRect().top;
            var windowHeight = window.innerHeight;

            if (petaSectionPosition < windowHeight / 2) {
                petaSection.classList.add('animate-left');
                iframeElement.classList.add('animate-left');
                storeInfoElement.classList.add('animate-right');
            } else {
                petaSection.classList.remove('animate-left');
                iframeElement.classList.remove('animate-left');
                storeInfoElement.classList.remove('animate-right');
            }
        }
    </script>

    <!-- //script animate Bookshelf Store -->
    <script>
       window.addEventListener('scroll', function() {
        var offlineStore = document.querySelector('.offline_store');
        var distanceFromTop = offlineStore.getBoundingClientRect().top;

        var windowHeight = window.innerHeight;

        if (distanceFromTop - windowHeight + 200 <= 0) {
            offlineStore.classList.add('show');
        } else {
            offlineStore.classList.remove('show');
        }
    });
    </script>









    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>

</body>

</html>