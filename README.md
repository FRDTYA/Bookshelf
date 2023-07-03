# README - Bookshelf

## Deskripsi

Bookshelf adalah sebuah website e-commerce yang menjual buku buku dalam berbagai category, dibuat dengan menggunakan HTML, CSS, JS , PHP dan MySQL sehingga website berhasil dibuat sebagaimana mestinya.

## Fitur

1. **Home**: Pengguna dapat melihat informasi awal tentang Bookshelf baik dari lokasi, foto - foto toko dan lainnya.

2. **Shop**: Pengguna dapat melihat daftar buku dan juga bisa mencari melalui search-bar dan juga filter untuk mempermudah pengguna dalam menemukan buku yang dicari.

3. **Orders**: Pengguna dapat melihat pesanan yang sebelumnya telah di checkout oleh si pengguna, sebagai history pembelian.

4. **Contact**: Pengguna dapat mengirim pesan untuk developer website bookshelf baik saran kritik ataupun complement dalam proses transaksi.

5. **Wishlist**: Pengguna dapat me wishlist buku - buku yang disukai ataupun yang nantinya akan pengguna beli.

6. **Cart/Keranjang**: Pengguna dapat memasukkan product buku - buku kedalam keranjang untuk nantinya bisa langsung ke proses checkout.

7. **Update Profile**: Pengguna dapat mengupdate profile dari foto, nama, password, dan email dari si pengguna.

8. **Save Address**: Pengguna dapat mengupdate atau menyimpan address/alamat untuk pengguna agar barang dapat sampai pada alamat yang telah disimpan.

9. **Admin Panel**: Admin panel dikhususkan untuk mengatur berbagai macam dalam kegiatan sebuah website toko buku. Admin dapat menambah product, merubah status transaksi pengguna yang melakukan order dan hal-hal lainnya.

## Pengaturan Pengembangan

1. Pastikan Anda memiliki versi PHP 8.20 atau yang lebih baru terpasang di sistem Anda.

2. Salin repositori ini ke direktori web server Anda.

3. Import file SQL yang disediakan (`frbooks.sql`) ke dalam database MySQL Anda menggunakan phpMyAdmin.

4. Konfigurasikan koneksi database MySQL dengan mengedit berkas `koneksi.php` dan mengganti nilai `$servername`, `$username`, `$password`, dan `$dbname` sesuai dengan pengaturan Anda.

```php
// Konfigurasi database
$servername = "localhost"; // nama host database
$username = "root"; // username database
$password = ""; // password database
$dbname = "frbooks"; // nama database
```

5. Akses halaman beranda melalui peramban web dengan mengunjungi URL `https://localhost/bookshelf` atau jika hosting masih aktif kunjungi URL `https://bookshelf.seceria.com`.

```user
// User Akun
Username : user123
Password : 123
```

6. Untuk mengakses halaman dasbor admin, buka URL `https://localhost/bookshelf` atau kunjungi URL `https://bookshelf.seceria.com` dan masukkan username dan password berikut :

```admin
// Admin Akun
Username : admin
Password : 12345
```

## Kredit

Bookshelf dikembangkan oleh Fachrio Raditya.

## Kontak

Jika Anda memiliki pertanyaan atau masalah terkait dengan Bookshelf, silakan hubungi Fachrio Raditya melalui email di fachrio.raditya@gmail.com.

Terima kasih telah menggunakan dan berkunjung di Bookshelf.
