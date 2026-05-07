<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST" || empty($_POST)) {
    header("Location: tambah.php");
    exit;
}

/* ================= INPUT ================= */
$nama = $_POST['nama'] ?? '';

$s1 = $_POST['smt1'] ?? 0;
$s2 = $_POST['smt2'] ?? 0;
$s3 = $_POST['smt3'] ?? 0;
$s4 = $_POST['smt4'] ?? 0;
$s5 = $_POST['smt5'] ?? 0;

$rata = ($s1 + $s2 + $s3 + $s4 + $s5) / 5;

$peringkat = $_POST['peringkat'] ?? 0;
$total = $_POST['total_siswa'] ?? 1;
$prodi = $_POST['prodi'] ?? '';
$ranking_sekolah = $_POST['ranking_sekolah'] ?? '';
$alumni = $_POST['alumni'] ?? 0;
$prestasi = $_POST['prestasi'] ?? '';

/* ================= DATA PRODI ================= */
$standar_prodi = [
    "Teknik Kimia D3" => 85.51,
    "Agribisnis" => 88.73,
    "Agroteknologi" => 88.67,
    "Akuntansi" => 91.55,
    "Ekonomi Pembangunan" => 90,
    "Hubungan Masyarakat" => 89.26,
    "Ilmu Administrasi Bisnis" => 90.72,
    "Ilmu Hubungan Internasional" => 89.21,
    "Ilmu Komunikasi" => 89.21,
    "Ilmu Tanah" => 86.46,
    "Manajemen" => 91.61,
    "Metalurgi" => 86.97,
    "Sistem Informasi" => 88.79,
    "Teknik Geofisika" => 86.48,
    "Teknik Geologi" => 90.11,
    "Teknik Geomatika" => 86.46,
    "Teknik Industri" => 90.42,
    "Teknik Informatika" => 91.41,
    "Teknik Kimia" => 87.49,
    "Teknik Lingkungan" => 88.62,
    "Teknik Perminyakan" => 90.79,
    "Teknik Pertambangan" => 91.75
];

$standar = $standar_prodi[$prodi] ?? 90;
$selisih = $rata - $standar;

/* ================= HITUNG SKOR ================= */
$skor = 50 + ($selisih * 5);

/* ranking */
$rasio = $peringkat / max($total,1);
$skor -= ($rasio * 30);

/* sekolah */
if ($ranking_sekolah == "1-500") $skor += 10;
elseif ($ranking_sekolah == "501-1000") $skor += 5;
else $skor -= 5;

/* alumni */
$skor += ($alumni * 0.5);

/* prestasi */
if ($prestasi == "kabupaten") $skor += 5;
elseif ($prestasi == "provinsi") $skor += 10;
elseif ($prestasi == "nasional") $skor += 15;

/* batas */
$skor = max(0, min(100, $skor));

/* kategori */
if ($skor >= 70) $kelas = "high";
elseif ($skor >= 40) $kelas = "medium";
else $kelas = "low";

/* ================= ANALISIS ================= */
$alasan = [];

$alasan[] = ($rata > $standar)
? "Nilai kamu di atas standar jurusan."
: "Nilai kamu masih di bawah standar jurusan.";

$alasan[] = ($rasio < 0.2)
? "Ranking kamu sangat bagus."
: "Ranking kamu kurang kompetitif.";

$alasan[] = ($ranking_sekolah == "1-500")
? "Sekolah termasuk unggulan."
: "Sekolah kurang kompetitif.";

$alasan[] = ($prestasi != "tidak")
? "Kamu punya prestasi pendukung."
: "Tidak ada prestasi tambahan.";

/* ================= REKOMENDASI ================= */
$rekomendasi = [];
foreach ($standar_prodi as $p => $nilai) {
    $sel = $rata - $nilai;

    if ($sel >= 2) $rekomendasi[$p] = "AMAN";
    elseif ($sel >= -1) $rekomendasi[$p] = "SEDANG";
    else $rekomendasi[$p] = "AMBISIUS";
}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<div class="container">
<div class="card">

<h2><?= $nama ?></h2>

<h3><?= $prodi ?></h3>

<h1 class="<?= $kelas ?>">
<?= number_format($skor,1) ?>%
</h1>

<div class="bar">
<div class="fill" id="bar"></div>
</div>

<!-- ANALISIS -->
<h3>Analisis</h3>
<ul>
<?php foreach ($alasan as $a) echo "<li>$a</li>"; ?>
</ul>

<!-- REKOMENDASI -->
<h3>Rekomendasi Jurusan</h3>
<?php
foreach ($rekomendasi as $p => $k){
    echo "<div>$p <span class='badge ".strtolower($k)."'>$k</span></div>";
}
?>

<br>
<a href="tambah.php"><button>Kembali</button></a>

</div>
</div>

<script>
document.getElementById("bar").style.width = "<?= $skor ?>%";
</script>

</body>
</html>