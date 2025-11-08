<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM kosan WHERE id='$id'");
$d = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kosan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h3>Edit Data Kosan</h3>
    <form action="edit_aksi.php" method="POST">
        <input type="hidden" name="id" value="<?= $d['id'] ?>">

        <div class="mb-3">
            <label>Nama Kosan</label>
            <input type="text" name="nama" value="<?= $d['nama'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" value="<?= $d['harga'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Fasilitas</label>
            <input type="text" name="fasilitas" value="<?= $d['fasilitas'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" value="<?= $d['lokasi'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Keamanan</label>
            <input type="text" name="keamanan" value="<?= $d['keamanan'] ?>" class="form-control" required>
        </div>

        <button class="btn btn-warning">Update</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>
