<?php
include 'koneksi.php';

$bobot = [0.30, 0.25, 0.25, 0.20];

$data = mysqli_query($koneksi, "SELECT * FROM kosan");
$matrix = [];
while ($d = mysqli_fetch_array($data)) {
    $matrix[] = [
        $d['id_kosan'], 
        $d['nama'], 
        $d['harga'], 
        $d['fasilitas'], 
        $d['lokasi'], 
        $d['keamanan']
    ];
}

$norm = [];
for ($j = 2; $j <= 5; $j++) {
    $sum = 0;
    foreach ($matrix as $m) {
        $sum += pow($m[$j], 2);
    }
    foreach ($matrix as $i => $m) {
        $norm[$i][$j] = $m[$j] / sqrt($sum);
    }
}

$topsis = [];
foreach ($norm as $i => $n) {
    $topsis[$i]['Y'] = [
        $n[2] * $bobot[0], 
        $n[3] * $bobot[1], 
        $n[4] * $bobot[2], 
        $n[5] * $bobot[3]
    ];
    $topsis[$i]['id'] = $matrix[$i][0];
    $topsis[$i]['nama'] = $matrix[$i][1];
}

$idealPlus = [
    min(array_column(array_column($topsis, 'Y'), 0)), // harga (cost)
    max(array_column(array_column($topsis, 'Y'), 1)),
    max(array_column(array_column($topsis, 'Y'), 2)),
    max(array_column(array_column($topsis, 'Y'), 3))
];

$idealMin = [
    max(array_column(array_column($topsis, 'Y'), 0)), // harga highest = worst
    min(array_column(array_column($topsis, 'Y'), 1)),
    min(array_column(array_column($topsis, 'Y'), 2)),
    min(array_column(array_column($topsis, 'Y'), 3))
];

foreach ($topsis as $i => $tp) {
    $dplus = $dmin = 0;
    for ($k = 0; $k < 4; $k++) {
        $dplus += pow($tp['Y'][$k] - $idealPlus[$k], 2);
        $dmin += pow($tp['Y'][$k] - $idealMin[$k], 2);
    }
    $topsis[$i]['dplus'] = sqrt($dplus);
    $topsis[$i]['dmin'] = sqrt($dmin);
    $topsis[$i]['score'] = $topsis[$i]['dmin'] / ($topsis[$i]['dmin'] + $topsis[$i]['dplus']);
}

usort($topsis, function($a, $b){
    return $b['score'] <=> $a['score'];
});

echo "<h3>Hasil Pemeringkatan TOPSIS</h3>";
echo "<table border='1' cellpadding='8'>
<tr><th>Nama Kos</th><th>Nilai Preferensi</th><th>Ranking</th></tr>";

$rank = 1;
foreach ($topsis as $tp) {
    echo "<tr>
        <td>{$tp['nama']}</td>
        <td>".round($tp['score'], 4)."</td>
        <td>{$rank}</td>
    </tr>";
    $rank++;
}
echo "</table>";
