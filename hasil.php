<<<<<<< HEAD
<?php
    session_start();
    require_once 'koneksi.php';

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        header("Location: nilai.php");
        exit();
    }

    // INPUT NILAI
    $s1 = (float)$_POST['s1'];
    $s2 = (float)$_POST['s2'];
    $s3 = (float)$_POST['s3'];
    $s4 = (float)$_POST['s4'];
    $s5 = (float)$_POST['s5'];

    $rata = ($s1 + $s2 + $s3 + $s4 + $s5) / 5;

    // HITUNG PRESTASI
    $prestasi = (int)$_POST['prestasi1'] + (int)$_POST['prestasi2'] + (int)$_POST['prestasi3'];

    // HITUNG PARALEL
    $eligible = (int)$_SESSION['eligible'];
    $ranking  = (int)$_SESSION['ranking'];

    $skorRanking = (($eligible - $ranking) / max($eligible, 1)) * 20;

    // HITUNG PELUANG
    function hitungPeluang($rata, $prestasi, $akreditasi, $peringkat, $alumni, $skorRanking, $standar) {
        $p  = (($rata * 0.6) + $prestasi + $akreditasi + $peringkat + $alumni + $skorRanking) / 1.5;
        $p -= ($standar - 85) * 1.5;

        return round(max(0, min(100, $p)), 1);
    }

    // DATA SESSION
    $namaProdi1 = $_SESSION['nama_prodi1'];
    $standar1   = (float)$_SESSION['standar_prodi1'];

    $namaProdi2 = $_SESSION['nama_prodi2'];
    $standar2   = (float)$_SESSION['standar_prodi2'];

    $akreditasi = (int)$_SESSION['akreditasi'];
    $peringkat  = (int)$_SESSION['peringkat'];
    $alumni     = (int)$_SESSION['alumni'];

    $peluang1 = hitungPeluang(
        $rata,
        $prestasi,
        $akreditasi,
        $peringkat,
        $alumni,
        $skorRanking,
        $standar1
    );

    $peluang2 = hitungPeluang(
        $rata,
        $prestasi,
        $akreditasi,
        $peringkat,
        $alumni,
        $skorRanking,
        $standar2
    );

    // STATUS PELUANG
    function statusPeluang($nilai) {
        if ($nilai >= 75) {
            return "PELUANG BESAR";
        } elseif ($nilai >= 50) {
            return "PELUANG SEDANG";
        } else {
            return "PELUANG KECIL";
        }
    }

    // SIMPAN DATABASE
    $nama = mysqli_real_escape_string($conn, $_SESSION['nama']);

    $sql = "INSERT INTO riwayat_prediksi
            (
                nama,
                akreditasi,
                peringkat,
                ranking,
                eligible,
                alumni,
                rata_rapor,
                prestasi,
                prodi1,
                standar1,
                peluang1,
                prodi2,
                standar2,
                peluang2
            )
            VALUES
            (
                '$nama',
                $akreditasi,
                $peringkat,
                $ranking,
                $eligible,
                $alumni,
                $rata,
                $prestasi,
                '" . mysqli_real_escape_string($conn, $namaProdi1) . "',
                $standar1,
                $peluang1,
                '" . mysqli_real_escape_string($conn, $namaProdi2) . "',
                $standar2,
                $peluang2
            )";

    mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Prediksi</title>

    <link rel="stylesheet" href="style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- HEADER -->
    <div class="top-header"
         style="background-color:#fdd7e3;
                justify-content:flex-start;
                margin-top:-15px;">

        <img src="logoupn.png" alt="Logo UPN">

        <div>
            <h2>Universitas Pembangunan Nasional "Veteran" Yogyakarta</h2>
            <p>Prediksi Peluang Lolos SNBP</p>
        </div>
    </div>

    <!-- CONTAINER -->
    <div class="container"
         style="flex-direction:column;
                align-items:center;
                gap:20px;">

        <div class="result-wrapper">

            <!-- =========================
                 PILIHAN 1
            ========================== -->
            <div class="result-card">

                <h2 class="result-title">Pilihan 1</h2>

                <p class="prodi-name">
                    <?= htmlspecialchars($namaProdi1) ?>
                </p>

                <div class="center">

                    <div class="percent">
                        <?= $peluang1 ?>%
                    </div>

                    <div class="progress">
                        <div class="progress-bar"
                             style="width:<?= $peluang1 ?>%;">
                        </div>
                    </div>

                    <div class="status">
                        <?= statusPeluang($peluang1) ?>
                    </div>

                </div>

                <div class="grid">

                    <div>
                        <b>Nama</b><br>
                        <?= htmlspecialchars($_SESSION['nama']) ?>
                    </div>

                    <div>
                        <b>Rata-rata</b><br>
                        <?= round($rata, 2) ?>
                    </div>

                    <div>
                        <b>Standar Prodi</b><br>
                        <?= $standar1 ?>
                    </div>

                    <div>
                        <b>Prestasi</b><br>
                        <?= $prestasi ?> Poin
                    </div>

                </div>

                <canvas id="chart1"></canvas>

                <div class="analisis">

                    <h3>Analisis</h3>

                    <ul>
                        <li>
                            Nilai rapor rata-rata
                            <b><?= round($rata,2) ?></b>
                            —
                            <?= $rata >= 88
                                ? 'cukup kompetitif untuk prodi ini.'
                                : 'perlu ditingkatkan.' ?>
                        </li>

                        <li>
                            Ranking paralel memberikan skor
                            <b><?= round($skorRanking,1) ?></b>
                            dari maksimal 20 poin.
                        </li>

                        <li>
                            Prestasi menambahkan
                            <b><?= $prestasi ?> poin</b>
                            ke perhitungan.
                        </li>
                    </ul>

                </div>

            </div>

            <!-- =========================
                 PILIHAN 2
            ========================== -->
            <div class="result-card">

                <h2 class="result-title">Pilihan 2</h2>

                <p class="prodi-name">
                    <?= htmlspecialchars($namaProdi2) ?>
                </p>

                <div class="center">

                    <div class="percent">
                        <?= $peluang2 ?>%
                    </div>

                    <!-- PROGRESS BAR BIRU PASTEL -->
                    <div class="progress">
                        <div class="progress-bar"
                             style="width:<?= $peluang2 ?>%;
                                    background:#8ec5ff;">
                        </div>
                    </div>

                    <div class="status">
                        <?= statusPeluang($peluang2) ?>
                    </div>

                </div>

                <div class="grid">

                    <div>
                        <b>Nama</b><br>
                        <?= htmlspecialchars($_SESSION['nama']) ?>
                    </div>

                    <div>
                        <b>Rata-rata</b><br>
                        <?= round($rata, 2) ?>
                    </div>

                    <div>
                        <b>Standar Prodi</b><br>
                        <?= $standar2 ?>
                    </div>

                    <div>
                        <b>Prestasi</b><br>
                        <?= $prestasi ?> Poin
                    </div>

                </div>

                <canvas id="chart2"></canvas>

                <div class="analisis">

                    <h3>Analisis</h3>

                    <ul>

                        <li>
                            Standar prodi ini
                            <b>
                                <?= $standar2 >= $standar1
                                    ? 'lebih tinggi'
                                    : 'lebih rendah' ?>
                            </b>
                            dari pilihan pertama.
                        </li>

                        <li>
                            Nilai
                            <?= $rata >= $standar2
                                ? 'memenuhi'
                                : 'belum memenuhi' ?>
                            standar rata-rata prodi.
                        </li>

                        <li>
                            Prestasi tetap memberi nilai tambah
                            pada perhitungan.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

        <!-- BUTTON -->
        <div style="display:flex; gap:50px;">

            <a href="index.php"
               class="btn-outline"
               style="background-color:#fdd7e3;
                      text-decoration:none;
                      white-space:nowrap;">

                Prediksi Ulang

            </a>

            <a href="riwayat.php"
               class="btn-outline"
               style="background-color:#fdd7e3;
                      text-decoration:none;">

                Lihat Riwayat

            </a>

        </div>

    </div>

    <!-- CHART -->
    <script>

        // CHART PILIHAN 1
        new Chart(document.getElementById('chart1'), {

            type: 'doughnut',

            data: {
                labels: ['Peluang Lolos', 'Tidak Lolos'],

                datasets: [{
                    data: [
                        <?= $peluang1 ?>,
                        <?= 100 - $peluang1 ?>
                    ],

                    backgroundColor: [
                        '#ff5f8f',
                        '#eeeeee'
                    ]
                }]
            },

            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }

        });


        // CHART PILIHAN 2
        new Chart(document.getElementById('chart2'), {

            type: 'doughnut',

            data: {
                labels: ['Peluang Lolos', 'Tidak Lolos'],

                datasets: [{
                    data: [
                        <?= $peluang2 ?>,
                        <?= 100 - $peluang2 ?>
                    ],

                    backgroundColor: [
                        '#8ec5ff',
                        '#eeeeee'
                    ]
                }]
            },

            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }

        });

    </script>

</body>
=======
<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        header("Location: nilai.php");
        exit();
    }

    // NILAI RAPOR
    $s1 = $_POST['s1'];
    $s2 = $_POST['s2'];
    $s3 = $_POST['s3'];
    $s4 = $_POST['s4'];
    $s5 = $_POST['s5'];

    $rata = ($s1 + $s2 + $s3 + $s4 + $s5) / 5;

    // PRESTASI
    $prestasi =
    $_POST['prestasi1'] +
    $_POST['prestasi2'] +
    $_POST['prestasi3'];

    // RANKING
    $ranking = (($_SESSION['eligible'] - $_SESSION['ranking']) / max($_SESSION['eligible'],1)) * 20;

    // DATA PRODI
    $namaProdi1 = $_SESSION['nama_prodi1'];
    $standar1 = $_SESSION['standar_prodi1'];

    $namaProdi2 = $_SESSION['nama_prodi2'];
    $standar2 = $_SESSION['standar_prodi2'];

    // HITUNG PILIHAN 1
    $peluang1 = (($rata * 0.6) + ($prestasi) + ($_SESSION['akreditasi']) + ($_SESSION['peringkat']) + ($_SESSION['alumni']) + ($ranking)) / 1.5;
    $peluang1 = $peluang1 - (($standar1 - 85) * 1.5);
    $peluang1 = max(0,min(100,$peluang1));
    $peluang1 = round($peluang1,1);

    // HITUNG PILIHAN 2
    $peluang2 = ( ($rata * 0.6) + ($prestasi) + ($_SESSION['akreditasi']) + ($_SESSION['peringkat']) + ($_SESSION['alumni']) + ($ranking)) / 1.5;
    $peluang2 = $peluang2 - (($standar2 - 85) * 1.5);
    $peluang2 = max(0,min(100,$peluang2));
    $peluang2 = round($peluang2,1);

    // STATUS
    function statusPeluang($nilai){
        if($nilai >= 75){
            return "PELUANG BESAR";
        } elseif($nilai >= 50){
            return "PELUANG SEDANG";
        } else{
            return "PELUANG KECIL";
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hasil Prediksi</title>

<link rel="stylesheet" href="style.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <div class="top-header">
        <img src="logoupn.png" alt="Logo UPN">
        <div>
            <h2>Universitas Pembangunan Nasional “Veteran” Yogyakarta</h2>
            <p>Prediksi Peluang Lolos SNBP</p>
        </div>
    </div>

    <div class="container">
        <div class="result-wrapper">
            <!-- PILIHAN 1 -->
            <div class="result-card">
                <h2 class="result-title">
                Pilihan 1
                </h2>

                <p class="prodi-name">
                <?= $namaProdi1; ?>
                </p>

                <div class="center">
                    <div class="percent">
                        <?= $peluang1 ?>%
                    </div>

                    <div class="progress">
                        <div class="progress-bar"
                            style="width:<?= $peluang1 ?>%">
                        </div>
                    </div>

                    <div class="status">
                        <?= statusPeluang($peluang1); ?>
                    </div>

                </div>

                <div class="grid">

                    <div>
                        <b>Nama</b><br>
                        <?= $_SESSION['nama']; ?>
                    </div>

                    <div>
                        <b>Rata-rata</b><br>
                        <?= round($rata,2); ?>
                    </div>

                    <div>
                        <b>Standar Prodi</b><br>
                        <?= $standar1; ?>
                    </div>

                    <div>
                        <b>Prestasi</b><br>
                        <?= $prestasi; ?> Poin
                    </div>

                </div>

                <canvas id="chart1"></canvas>

                <div class="analisis">
                    <h3>Analisis</h3>
                    <ul>
                        <li>Nilai rapor cukup kompetitif.</li>
                        <li>Ranking paralel mendukung peluang SNBP.</li>
                        <li>Prestasi meningkatkan peluang lolos.</li>
                    </ul>

                </div>

            </div>

            <!-- PILIHAN 2 -->

            <div class="result-card">
                <h2 class="result-title">
                    Pilihan 2
                </h2>

                <p class="prodi-name">
                    <?= $namaProdi2; ?>
                </p>

                <div class="center">
                    <div class="percent">
                        <?= $peluang2 ?>%
                    </div>

                    <div class="progress">
                        <div class="progress-bar"
                            style="width:<?= $peluang2 ?>%">
                        </div>
                    </div>

                    <div class="status">
                        <?= statusPeluang($peluang2); ?>
                    </div>
                </div>

                <div class="grid">
                    <div>
                        <b>Nama</b><br>
                        <?= $_SESSION['nama']; ?>
                    </div>

                    <div>
                        <b>Rata-rata</b><br>
                        <?= round($rata,2); ?>
                    </div>

                    <div>
                        <b>Standar Prodi</b><br>
                        <?= $standar2; ?>
                    </div>

                    <div>
                        <b>Prestasi</b><br>
                        <?= $prestasi; ?> Poin
                    </div>
                </div>

                <canvas id="chart2"></canvas>

                <div class="analisis">
                    <h3>Analisis</h3>
                    <ul>
                        <li>Persaingan prodi pilihan kedua berbeda.</li>
                        <li>Nilai masih cukup kompetitif.</li>
                        <li>Prestasi tetap memberi nilai tambah.</li>
                    </ul>

                </div>

            </div>

        </div>

    </div>

    <script>
        new Chart(document.getElementById('chart1'), {
            type:'doughnut',

            data:{
                labels:['Lolos','Tidak'],
                datasets:[{
                    data:[ <?= $peluang1 ?>, <?= 100-$peluang1 ?> ],
                    backgroundColor:['#ff5f8f','#eeeeee']
                }]
            }

        });

        new Chart(document.getElementById('chart2'), {
            type:'doughnut',

            data:{
                labels:['Lolos','Tidak'],
                datasets:[{
                    data:[ <?= $peluang2 ?>, <?= 100-$peluang2 ?> ],
                    backgroundColor:['#7b3ff2','#eeeeee']
                }]
            }

        });

    </script>

</body>
>>>>>>> 7571be16df99208c22d146bdab72c1bea6232a6a
</html>