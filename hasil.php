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
</html>