<?php

include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['qty'];

    // Validasi kuantitas
    if (!is_numeric($qty) || $qty < 1 || $qty > 99) {
        echo 'Invalid quantity';
        exit;
    }

    $update_qty = $conn->prepare("UPDATE `tb_cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$qty, $cart_id]);

    // Jika berhasil memperbarui kuantitas
    echo 'Quantity updated successfully';
}
?>