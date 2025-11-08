<?php
include 'koneksi.php';

mysqli_query($koneksi, "INSERT INTO kosan VALUES (
    '',
    '$_POST[nama]',
    '$_POST[harga]',
    '$_POST[fasilitas]',
    '$_POST[lokasi]',
    '$_POST[keamanan]'
)");

header("location:index.php");
?>
