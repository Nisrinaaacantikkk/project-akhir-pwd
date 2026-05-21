<?php
    /**
     * hasil.php
     * Halaman inti: menghitung dan menampilkan hasil prediksi peluang SNBP.
     *
     * RUMUS PERHITUNGAN PELUANG:
     * 1. Rata-rata rapor      = (s1+s2+s3+s4+s5) / 5
     * 2. Skor ranking paralel = ((eligible - ranking) / eligible) × 20
     * 3. Total poin prestasi  = prestasi1 + prestasi2 + prestasi3
     *
     * 4. Peluang (sebelum penalti):
     *    p = ((rata × 0.6) + prestasi + akreditasi + peringkat + alumni + skorRanking) / 1.5
     *
     * 5. Penalti standar prodi:
     *    penalti = (standar_prodi - 85) × 1.5
     *
     * 6. Peluang akhir = p - penalti  (dibatasi antara 0 dan 100)
     */

    session_start();      // Lanjutkan sesi untuk membaca data dari halaman sebelumnya
    require_once 'koneksi.php'; // Hubungkan ke database untuk menyimpan hasil

    // Pastikan halaman diakses melalui form POST, bukan langsung dari URL
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        header("Location: nilai.php");
        exit();
    }

    // AMBIL & HITUNG NILAI RAPOR
    $s1 = (float)$_POST['s1'];
    $s2 = (float)$_POST['s2'];
    $s3 = (float)$_POST['s3'];
    $s4 = (float)$_POST['s4'];
    $s5 = (float)$_POST['s5'];

    // Hitung rata-rata dari 5 semester
    $rata = ($s1 + $s2 + $s3 + $s4 + $s5) / 5;

    // HITUNG TOTAL POIN PRESTASI
    // Jumlahkan poin dari ketiga prestasi yang dipilih
    // Nilai: Kabupaten=5, Provinsi=8, Nasional=12, Tidak Ada=0
    $prestasi = (int)$_POST['prestasi1'] + (int)$_POST['prestasi2'] + (int)$_POST['prestasi3'];

    // HITUNG SKOR RANKING PARALEL
    // Rumus: ((jumlah_eligible - ranking_siswa) / jumlah_eligible) × 20
    // Semakin tinggi ranking (angka kecil), semakin besar skor yang didapat.
    // max($eligible, 1) mencegah pembagian dengan nol jika eligible = 0
    $eligible    = (int)$_SESSION['eligible'];
    $ranking     = (int)$_SESSION['ranking'];
    $skorRanking = (($eligible - $ranking) / max($eligible, 1)) * 20;

    // FUNGSI UTAMA: HITUNG PELUANG
    //
    // Parameter:
    //   $rata        = rata-rata nilai rapor 5 semester
    //   $prestasi    = total poin prestasi (0–36)
    //   $akreditasi  = poin akreditasi sekolah (4/8/12)
    //   $peringkat   = poin peringkat nasional sekolah (5/8/10)
    //   $alumni      = jumlah alumni lolos SNBP (langsung sebagai poin)
    //   $skorRanking = skor ranking paralel (0–20)
    //   $standar     = nilai standar/passing grade prodi yang dipilih
    //
    // Cara kerja:
    //   1. Gabungkan semua faktor → bagi 1.5 untuk normalisasi ke skala 0–100
    //   2. Kurangi penalti berdasarkan seberapa tinggi standar prodi
    //      (prodi dengan standar 91 lebih sulit dari prodi standar 85)
    //   3. Batasi hasil antara 0–100 agar tidak negatif atau lebih dari 100
    function hitungPeluang($rata, $prestasi, $akreditasi, $peringkat, $alumni, $skorRanking, $standar) {
        // Gabungkan semua faktor dan normalisasi
        $p  = (($rata * 0.6) + $prestasi + $akreditasi + $peringkat + $alumni + $skorRanking) / 1.5;

        // Kurangi penalti berdasarkan tingkat kesulitan prodi
        // Prodi dengan standar > 85 mendapat penalti lebih besar
        $p -= ($standar - 85) * 1.5;

        // Bulatkan ke 1 desimal, pastikan hasil antara 0 dan 100
        return round(max(0, min(100, $p)), 1);
    }

    // AMBIL DATA DARI SESI
    // Data ini disimpan di halaman-halaman sebelumnya
    $namaProdi1 = $_SESSION['nama_prodi1'];
    $standar1   = (float)$_SESSION['standar_prodi1'];
    $namaProdi2 = $_SESSION['nama_prodi2'];
    $standar2   = (float)$_SESSION['standar_prodi2'];
    $akreditasi = (int)$_SESSION['akreditasi'];
    $peringkat  = (int)$_SESSION['peringkat'];
    $alumni     = (int)$_SESSION['alumni'];

    // Hitung peluang untuk masing-masing pilihan prodi
    $peluang1 = hitungPeluang($rata, $prestasi, $akreditasi, $peringkat, $alumni, $skorRanking, $standar1);
    $peluang2 = hitungPeluang($rata, $prestasi, $akreditasi, $peringkat, $alumni, $skorRanking, $standar2);

    // FUNGSI STATUS PELUANG
    // Mengubah angka persentase menjadi label kategori
    // ≥75% → PELUANG BESAR | 50–74% → PELUANG SEDANG | <50% → PELUANG KECIL
    function statusPeluang($nilai) {
        if ($nilai >= 75) {
            return "PELUANG BESAR";
        } elseif ($nilai >= 50) {
            return "PELUANG SEDANG";
        } else {
            return "PELUANG KECIL";
        }
    }

    // SIMPAN HASIL KE DATABASE
    // Semua data prediksi disimpan ke tabel riwayat_prediksi
    // mysqli_real_escape_string() mencegah serangan SQL Injection
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

    mysqli_query($conn, $sql); // Jalankan query INSERT ke database
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Prediksi</title>
    <link rel="stylesheet" href="style.css">
    <!-- Library Chart.js untuk menampilkan grafik donut -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Header universitas -->
    <div class="top-header"
         style="background-color:#fdd7e3; justify-content:flex-start; margin-top:-15px;">
        <img src="logoupn.png" alt="Logo UPN">
        <div>
            <h2>Universitas Pembangunan Nasional "Veteran" Yogyakarta</h2>
            <p>Prediksi Peluang Lolos SNBP</p>
        </div>
    </div>

    <!-- Wrapper hasil: dua kartu berdampingan (pilihan 1 & 2) -->
    <div class="container" style="flex-direction:column; align-items:center; gap:20px;">
        <div class="result-wrapper">

            <!-- 
                 KARTU HASIL PILIHAN 1
             -->
            <div class="result-card">
                <h2 class="result-title">Pilihan 1</h2>

                <!-- Nama program studi yang dipilih -->
                <p class="prodi-name">
                    <?= htmlspecialchars($namaProdi1) ?>
                </p>

                <div class="center">
                    <!-- Tampilkan persentase peluang dalam angka besar -->
                    <div class="percent">
                        <?= $peluang1 ?>%
                    </div>

                    <!-- Progress bar visual (lebar = persentase peluang) -->
                    <div class="progress">
                        <div class="progress-bar" style="width:<?= $peluang1 ?>%;"></div>
                    </div>

                    <!-- Label status: PELUANG BESAR / SEDANG / KECIL -->
                    <div class="status">
                        <?= statusPeluang($peluang1) ?>
                    </div>
                </div>

                <!-- Grid info ringkas: nama, rata-rata, standar prodi, poin prestasi -->
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

                <!-- Grafik donut Chart.js untuk pilihan 1 (warna pink) -->
                <canvas id="chart1"></canvas>

                <!-- 
                     ANALISIS OTOMATIS
                     Sistem memberikan komentar berdasarkan data pengguna
                 -->
                <div class="analisis">
                    <h3>Analisis</h3>
                    <ul>
                        <li>
                            Nilai rapor rata-rata
                            <b><?= round($rata,2) ?></b>
                            —
                            <!-- Jika rata ≥88 dianggap kompetitif, jika tidak perlu ditingkatkan -->
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

            <!-- 
                 KARTU HASIL PILIHAN 2
             -->
            <div class="result-card">
                <h2 class="result-title">Pilihan 2</h2>

                <!-- Nama program studi pilihan 2 -->
                <p class="prodi-name">
                    <?= htmlspecialchars($namaProdi2) ?>
                </p>

                <div class="center">
                    <!-- Persentase peluang pilihan 2 -->
                    <div class="percent">
                        <?= $peluang2 ?>%
                    </div>

                    <!-- Progress bar biru pastel untuk pilihan 2 -->
                    <div class="progress">
                        <div class="progress-bar"
                             style="width:<?= $peluang2 ?>%; background:#8ec5ff;">
                        </div>
                    </div>

                    <!-- Label status pilihan 2 -->
                    <div class="status">
                        <?= statusPeluang($peluang2) ?>
                    </div>
                </div>

                <!-- Grid info ringkas pilihan 2 -->
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

                <!-- Grafik donut Chart.js untuk pilihan 2 (warna biru pastel) -->
                <canvas id="chart2"></canvas>

                <!-- Analisis otomatis pilihan 2 -->
                <div class="analisis">
                    <h3>Analisis</h3>
                    <ul>
                        <li>
                            Standar prodi ini
                            <b>
                                <!-- Bandingkan standar prodi 2 vs prodi 1 -->
                                <?= $standar2 >= $standar1 ? 'lebih tinggi' : 'lebih rendah' ?>
                            </b>
                            dari pilihan pertama.
                        </li>
                        <li>
                            Nilai
                            <!-- Apakah rata-rata rapor memenuhi standar prodi 2? -->
                            <?= $rata >= $standar2 ? 'memenuhi' : 'belum memenuhi' ?>
                            standar rata-rata prodi.
                        </li>
                        <li>
                            Prestasi tetap memberi nilai tambah pada perhitungan.
                        </li>
                    </ul>
                </div>
            </div>

        </div><!-- end result-wrapper -->

        <!-- Tombol aksi di bawah kedua kartu -->
        <div style="display:flex; gap:50px;">
            <!-- Tombol prediksi ulang → kembali ke halaman awal -->
            <a href="index.php" class="btn-outline"
               style="background-color:#fdd7e3; text-decoration:none; white-space:nowrap;">
                Prediksi Ulang
            </a>
            <!-- Tombol lihat riwayat semua prediksi -->
            <a href="riwayat.php" class="btn-outline"
               style="background-color:#fdd7e3; text-decoration:none;">
                Lihat Riwayat
            </a>
        </div>

    </div><!-- end container -->

    <!-- 
         JAVASCRIPT: BUAT GRAFIK DONUT
         Menggunakan Chart.js untuk visualisasi persentase.
         - Data: [peluang_lolos, tidak_lolos] → contoh: [72, 28]
         - Warna: pink untuk pilihan 1, biru pastel untuk pilihan 2
     -->
    <script>
        // Grafik donut untuk Pilihan 1 (warna pink)
        new Chart(document.getElementById('chart1'), {
            type: 'doughnut',
            data: {
                labels: ['Peluang Lolos', 'Tidak Lolos'],
                datasets: [{
                    data: [
                        <?= $peluang1 ?>,           // Persentase peluang lolos
                        <?= 100 - $peluang1 ?>      // Sisa (100% - peluang lolos)
                    ],
                    backgroundColor: [
                        '#ff5f8f',  // Warna pink untuk "Peluang Lolos"
                        '#eeeeee'   // Warna abu untuk "Tidak Lolos"
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' } // Legenda di bawah grafik
                }
            }
        });

        // Grafik donut untuk Pilihan 2 (warna biru pastel)
        new Chart(document.getElementById('chart2'), {
            type: 'doughnut',
            data: {
                labels: ['Peluang Lolos', 'Tidak Lolos'],
                datasets: [{
                    data: [
                        <?= $peluang2 ?>,           // Persentase peluang lolos
                        <?= 100 - $peluang2 ?>      // Sisa
                    ],
                    backgroundColor: [
                        '#8ec5ff',  // Warna biru pastel untuk "Peluang Lolos"
                        '#eeeeee'   // Warna abu untuk "Tidak Lolos"
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>

</body>
</html>