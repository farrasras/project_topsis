<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_kosan");

// cek koneksi
if (!$koneksi) {
    echo "Koneksi gagal: " . mysqli_connect_error();
}
?>
