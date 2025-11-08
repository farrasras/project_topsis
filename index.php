<?php
include 'koneksi.php';
$query = mysqli_query($koneksi, "SELECT * FROM kosan");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kosan - TOPSIS</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef3f7;
        }
        .table-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        h2 {
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">📌 Data Kosan - Metode TOPSIS</h2>

    <div class="table-container">
        <div class="d-flex justify-content-between mb-3">
            <a href="tambah.php" class="btn btn-success">➕ Tambah Data</a>
            <a href="hitung_topsis.php" class="btn btn-primary">⚙️ Hitung TOPSIS</a>
        </div>

        <table class="table table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Kosan</th>
                    <th>Harga</th>
                    <th>Fasilitas</th>
                    <th>Lokasi</th>
                    <th>Keamanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) { ?>
                <tr class="text-center">
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['fasilitas'] ?></td>
                    <td><?= $row['lokasi'] ?></td>
                    <td><?= $row['keamanan'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
