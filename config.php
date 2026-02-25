<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "taskflow_db";

// buat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// echo "Koneksi berhasil"; // aktifkan ini jika mau test

?>