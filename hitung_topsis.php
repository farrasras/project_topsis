<?php
include 'koneksi.php';

// Ambil data kosan
$data = mysqli_query($koneksi, "SELECT * FROM kosan");
$kosan = [];
while ($d = mysqli_fetch_assoc($data)) {
    $kosan[] = $d;
}

// Konversi teks ke angka (bobot)
function konversi($kolom, $nilai)
{
    $bobot = 0;
    if ($kolom == 'fasilitas') {
        if (str_contains(strtolower($nilai), 'wifi') && str_contains(strtolower($nilai), 'ac')) $bobot = 5;
        elseif (str_contains(strtolower($nilai), 'wifi')) $bobot = 4;
        elseif (str_contains(strtolower($nilai), 'ac')) $bobot = 3;
        else $bobot = 2;
    } 
    elseif ($kolom == 'lokasi') {
        if (str_contains(strtolower($nilai), 'dekat')) $bobot = 5;
        elseif (str_contains(strtolower($nilai), 'agak')) $bobot = 3;
        else $bobot = 2;
    }
    elseif ($kolom == 'keamanan') {
        if (strtolower($nilai) == 'sangat baik') $bobot = 5;
        elseif (strtolower($nilai) == 'bagus') $bobot = 4;
        elseif (strtolower($nilai) == 'sedang') $bobot = 3;
        else $bobot = 2;
    }
    return $bobot;
}

// Bobot tiap kriteria
$bobot = [
    'harga' => 0.4,      // Cost
    'fasilitas' => 0.25, // Benefit
    'lokasi' => 0.2,     // Benefit
    'keamanan' => 0.15   // Benefit
];

// Hitung nilai matriks keputusan
$matriks = [];
foreach ($kosan as $i => $k) {
    $matriks[$i] = [
        'harga' => $k['harga'],
        'fasilitas' => konversi('fasilitas', $k['fasilitas']),
        'lokasi' => konversi('lokasi', $k['lokasi']),
        'keamanan' => konversi('keamanan', $k['keamanan'])
    ];
}

// Hitung normalisasi
$normal = [];
$sum = [];
foreach ($matriks[0] as $key => $val) {
    $sum[$key] = 0;
    foreach ($matriks as $m) {
        $sum[$key] += pow($m[$key], 2);
    }
    $sum[$key] = sqrt($sum[$key]);
}

foreach ($matriks as $i => $m) {
    foreach ($m as $key => $val) {
        $normal[$i][$key] = $val / $sum[$key];
    }
}

// Hitung normalisasi terbobot
$terbobot = [];
foreach ($normal as $i => $m) {
    foreach ($m as $key => $val) {
        $terbobot[$i][$key] = $val * $bobot[$key];
    }
}

// Tentukan solusi ideal
$idealPlus = [];
$idealMinus = [];

foreach ($bobot as $key => $b) {
    $values = array_column($terbobot, $key);
    $idealPlus[$key] = ($key == 'harga') ? min($values) : max($values);
    $idealMinus[$key] = ($key == 'harga') ? max($values) : min($values);
}

// Hitung jarak
$hasil = [];

foreach ($terbobot as $i => $m) {
    $dPlus = 0;
    $dMin = 0;

    foreach ($m as $key => $val) {
        $dPlus += pow($val - $idealPlus[$key], 2);
        $dMin += pow($val - $idealMinus[$key], 2);
    }

    $dPlus = sqrt($dPlus);
    $dMin = sqrt($dMin);

    // Closeness coefficient
    $cc = $dMin / ($dMin + $dPlus);

    $hasil[] = [
        'nama' => $kosan[$i]['nama'],
        'cc' => $cc
    ];
}

// Urutkan ranking
usort($hasil, function ($a, $b) {
    return $b['cc'] <=> $a['cc'];
});

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ranking TOPSIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h3 class="text-center mb-4">Hasil Perhitungan TOPSIS</h3>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>Ranking</th>
                <th>Nama Kosan</th>
                <th>Nilai TOPSIS</th>
            </tr>
        </thead>
        <tbody>
            <?php $rank = 1; foreach ($hasil as $h) { ?>
            <tr>
                <td class="text-center"><?= $rank++ ?></td>
                <td><?= $h['nama'] ?></td>
                <td class="text-center"><?= round($h['cc'], 4) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary">Kembali</a>
</div>

</body>
</html>
