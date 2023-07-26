<?php
require_once('tcpdf/tcpdf.php');
include('koneksi.php');

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
} else {
    $order_id = '';
}

// Mendapatkan data order dari database
if ($order_id != '') {
    $order_query = mysqli_query($conn, "SELECT * FROM `tb_orders` WHERE id = '$order_id'") or die('Query failed'); // Tambahkan kondisi WHERE untuk memfilter berdasarkan order_id
    if (mysqli_num_rows($order_query) > 0) {
        $fetch_orders = mysqli_fetch_assoc($order_query);
    } else {
        $fetch_orders = null;
    }
} else {
    $fetch_orders = null;
}

// Cek apakah ada data order yang ditemukan
if ($fetch_orders != null) {
    // Membuat instance TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set dokumen informasi
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Admin_FR');
    $pdf->SetTitle('Invoice');
    $pdf->SetSubject('Invoice');
    $pdf->SetKeywords('Invoice, PDF, Example');

    // Atur header dan footer
    $pdf->setHeaderData('image/original.png', 10, 'Invoice - ' . $fetch_orders['order_id'],'BOOKSHELF', array(0, 64, 255), array(0, 64, 128)); // Menggunakan $order_id
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

    // Atur ukuran font utama
    $pdf->setFontSize(12);

    // Tambahkan halaman
    $pdf->AddPage();

    // Konten PDF
    $content = '
    <div class="box">
        <p>placed on : <span>' . $fetch_orders['placed_on'] . '</span></p>
        <p>name : <span>' . $fetch_orders['name'] . '</span></p>
        <p>order id : <span>' . $fetch_orders['order_id'] . '</span></p>
        <p>sistem order : <span>' . $fetch_orders['sistem_order'] . '</span></p>
        <p>email : <span>' . $fetch_orders['email'] . '</span></p>
        <p>number : <span>' . $fetch_orders['number'] . '</span></p>
        <p>address : <span>' . $fetch_orders['address'] . '</span></p>
        <p>payment method : <span>' . $fetch_orders['method'] . '</span></p>
        <p>your orders : <span>' . $fetch_orders['total_products'] . '</span></p>
        <p>total price : <span>Rp ' . number_format($fetch_orders['total_price'], 0, ',', '.') . ',-</span></p>
        <p> payment status :
            <span style="color:' . (($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green') . '">
                ' . $fetch_orders['payment_status'] . (($fetch_orders['payment_status'] == 'completed') ? '' : '') . '
            </span>
        </p>
    </div>
    ';

    // Tulis konten ke halaman
    $pdf->writeHTML($content, true, false, true, false, '');

    // Output file PDF
    ob_end_clean(); // Tambahkan ini untuk membersihkan output sebelum mengirimkan file PDF
    $pdf->Output('Admin-invoice-Bookshelf_'.$fetch_orders['id'].'.pdf', 'D');
} else {
    echo "No order data found.";
}
?>
