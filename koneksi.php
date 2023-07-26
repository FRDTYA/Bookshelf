<?php
$servername = "localhost"; // nama host database
$username = "root"; // username database
$password = ""; // password database
$dbname = "frbooks"; // nama database

// Buat koneksi database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi database
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
