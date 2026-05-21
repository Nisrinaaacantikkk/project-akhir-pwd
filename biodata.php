<?php
    /**
     * biodata.php
     * Halaman kedua dari alur prediksi.
     * Menerima pilihan prodi dari index.php,
     * lalu meminta pengguna mengisi data diri dan informasi sekolah.
     */

    session_start(); // Memulai sesi untuk menyimpan data antar halaman

    // Hanya proses jika form dikirim lewat POST (dari index.php)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Memisahkan nilai prodi yang dikirim dalam format "NamaProdi|NilaiStandar"
        // Contoh: "Akuntansi|91.55" → $prodi1[0]="Akuntansi", $prodi1[1]="91.55"
        $prodi1 = explode("|", $_POST['prodi1']);
        $prodi2 = explode("|", $_POST['prodi2']);

        // Simpan nama dan standar nilai kedua prodi ke sesi
        // Sesi digunakan supaya data bisa diakses di halaman berikutnya (nilai.php & hasil.php)
        $_SESSION['nama_prodi1']    = $prodi1[0];
        $_SESSION['standar_prodi1'] = (float)$prodi1[1]; // Konversi ke desimal
        $_SESSION['nama_prodi2']    = $prodi2[0];
        $_SESSION['standar_prodi2'] = (float)$prodi2[1]; // Konversi ke desimal

    } else {
        // Jika diakses langsung tanpa POST, kembalikan ke halaman awal
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header universitas -->
    <div class="top-header" style="background-color: #fdd7e3; justify-content:flex-start; margin-top: -15px;">
        <img src="logoupn.png" alt="Logo UPN">
        <div>
            <h2>Universitas Pembangunan Nasional "Veteran" Yogyakarta</h2>
            <p>Prediksi Peluang Lolos SNBP</p>
        </div>
    </div>

    <div class="container">
        <div class="card">

            <!-- 
                 INDIKATOR LANGKAH (STEP)
                 Menunjukkan posisi pengguna dalam alur 4 langkah.
                 Lingkaran aktif (pink) = langkah yang sudah/sedang dilakukan.
                 Halaman ini = langkah 1 dan 2 aktif.
             -->
            <div class="step">
                <div class="active"></div> <!-- Langkah 1: Pilih Prodi ✓ -->
                <div class="active"></div> <!-- Langkah 2: Biodata (sekarang) -->
                <div></div>               <!-- Langkah 3: Nilai (belum) -->
                <div></div>               <!-- Langkah 4: Hasil (belum) -->
            </div>

            <h2>Input Biodata &amp; Sekolah</h2>

            <!-- 
                 FORM BIODATA DAN DATA SEKOLAH
                 Data dikirim ke nilai.php menggunakan POST
             -->
            <form action="nilai.php" method="POST">

                <!-- Nama lengkap siswa -->
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required>

                <!-- 
                     AKREDITASI SEKOLAH
                     Dikonversi ke poin untuk perhitungan:
                     A = 12 poin | B = 8 poin | C = 4 poin
                 -->
                <label>Akreditasi Sekolah</label>
                <select name="akreditasi" required>
                    <option value="12">A</option> <!-- Akreditasi A = 12 poin -->
                    <option value="8">B</option>  <!-- Akreditasi B = 8 poin -->
                    <option value="4">C</option>  <!-- Akreditasi C = 4 poin -->
                </select>

                <!-- 
                     PERINGKAT NASIONAL SEKOLAH
                     Menunjukkan seberapa baik sekolah secara nasional.
                     Dikonversi ke poin: Top 500 = 10 | Top 1000 = 8 | Lainnya = 5
                 -->
                <label>Peringkat Sekolah Nasional</label>
                <select name="peringkat" required>
                    <option value="10">1 – 500</option>   <!-- Sekolah terbaik = 10 poin -->
                    <option value="8">501 – 1000</option> <!-- Menengah = 8 poin -->
                    <option value="5">1000+</option>       <!-- Di luar top 1000 = 5 poin -->
                </select>

                <!-- 
                     RANKING PARALEL & JUMLAH ELIGIBLE
                     Digunakan untuk menghitung skor kompetisi di sekolah sendiri.
                     Rumus: ((eligible - ranking) / eligible) × 20
                     Contoh: ranking 5 dari 100 siswa eligible
                     → (100-5)/100 × 20 = 19 poin (mendekati maksimal)
                 -->
                <div class="row">
                    <div>
                        <label>Ranking Paralel</label>
                        <!-- Posisi siswa di antara semua siswa eligible di sekolahnya -->
                        <input type="number" name="ranking" min="1" required>
                    </div>
                    <div>
                        <label>Jumlah Eligible</label>
                        <!-- Total siswa yang memenuhi syarat (eligible) SNBP di sekolah -->
                        <input type="number" name="eligible" min="1" required>
                    </div>
                </div>

                <!-- 
                     ALUMNI LOLOS SNBP
                     Jumlah alumni sekolah yang pernah lolos SNBP ke UPN.
                     Semakin banyak alumni lolos, semakin tinggi poin bonus.
                 -->
                <label>Jumlah Alumni Lolos SNBP</label>
                <input type="number" name="alumni" min="0" required>

                <!-- Tombol lanjut ke halaman input nilai -->
                <button type="submit" class="btn">
                    Lanjut ke Input Nilai →
                </button>
            </form>

            <!-- Tombol kembali ke halaman sebelumnya -->
            <button onclick="history.back()" class="btn-outline">
                ← Kembali
            </button>

        </div>
    </div>

</body>
</html>