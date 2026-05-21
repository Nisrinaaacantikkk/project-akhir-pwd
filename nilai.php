<?php
    /**
     * nilai.php
     * Halaman ketiga dari alur prediksi.
     * Menerima data biodata dari biodata.php,
     * lalu meminta pengguna mengisi nilai rapor semester 1-5 dan prestasi.
     */

    session_start(); // Lanjutkan sesi yang sudah dimulai di biodata.php

    // Hanya proses jika form dikirim lewat POST (dari biodata.php)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Simpan semua data biodata ke sesi
        // htmlspecialchars() mencegah serangan XSS pada input teks
        // trim() menghapus spasi di awal/akhir nama
        $_SESSION['nama']       = htmlspecialchars(trim($_POST['nama']));

        // Konversi ke integer untuk keamanan dan konsistensi data
        $_SESSION['akreditasi'] = (int)$_POST['akreditasi']; // Poin akreditasi (4/8/12)
        $_SESSION['peringkat']  = (int)$_POST['peringkat'];  // Poin peringkat sekolah (5/8/10)
        $_SESSION['ranking']    = (int)$_POST['ranking'];    // Posisi ranking paralel siswa
        $_SESSION['eligible']   = (int)$_POST['eligible'];   // Total siswa eligible di sekolah
        $_SESSION['alumni']     = (int)$_POST['alumni'];     // Jumlah alumni yang lolos SNBP

    } else {
        // Jika diakses langsung tanpa POST, kembalikan ke biodata
        header("Location: biodata.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai</title>
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

            <!-- ===========================
                 INDIKATOR LANGKAH (STEP)
                 Halaman ini = langkah 1, 2, dan 3 aktif.
            ============================ -->
            <div class="step">
                <div class="active"></div> <!-- Langkah 1: Pilih Prodi ✓ -->
                <div class="active"></div> <!-- Langkah 2: Biodata ✓ -->
                <div class="active"></div> <!-- Langkah 3: Nilai (sekarang) -->
                <div></div>               <!-- Langkah 4: Hasil (belum) -->
            </div>

            <h2>Input Nilai Rapor</h2>

            <!-- ===========================
                 FORM INPUT NILAI RAPOR & PRESTASI
                 Data dikirim ke hasil.php untuk dihitung peluangnya.
                 Nilai rapor: min=1, max=100, bisa desimal (step=0.01)
            ============================ -->
            <form action="hasil.php" method="POST">

                <!-- Input nilai rapor semester 1 dan 2 (sejajar/berdampingan) -->
                <div class="row">
                    <div>
                        <label>Semester 1</label>
                        <input type="number" step="0.01" name="s1" min="1" max="100" required>
                    </div>
                    <div>
                        <label>Semester 2</label>
                        <input type="number" step="0.01" name="s2" min="1" max="100" required>
                    </div>
                </div>

                <!-- Input nilai rapor semester 3 dan 4 (sejajar/berdampingan) -->
                <div class="row">
                    <div>
                        <label>Semester 3</label>
                        <input type="number" step="0.01" name="s3" min="1" max="100" required>
                    </div>
                    <div>
                        <label>Semester 4</label>
                        <input type="number" step="0.01" name="s4" min="1" max="100" required>
                    </div>
                </div>

                <!-- Input nilai rapor semester 5 (satu baris penuh) -->
                <label>Semester 5</label>
                <input type="number" step="0.01" name="s5" min="1" max="100" required>

                <!-- ===========================
                     INPUT PRESTASI (OPSIONAL)
                     Pengguna bisa mengisi hingga 3 prestasi.
                     Setiap prestasi dikonversi ke poin:
                     - Kabupaten  = 5 poin
                     - Provinsi   = 8 poin
                     - Nasional   = 12 poin
                     - Tidak Ada  = 0 poin
                     Total maksimal prestasi: 12 + 12 + 12 = 36 poin
                ============================ -->
                <h3 style="margin-top:30px;">Prestasi (Opsional)</h3>

                <!-- Prestasi 1 dan 2 (sejajar/berdampingan) -->
                <div class="row">
                    <div>
                        <label>Prestasi 1</label>
                        <select name="prestasi1">
                            <option value="0">Tidak Ada</option>
                            <option value="5">Kabupaten</option>  <!-- +5 poin -->
                            <option value="8">Provinsi</option>   <!-- +8 poin -->
                            <option value="12">Nasional</option>  <!-- +12 poin -->
                        </select>
                    </div>
                    <div>
                        <label>Prestasi 2</label>
                        <select name="prestasi2">
                            <option value="0">Tidak Ada</option>
                            <option value="5">Kabupaten</option>
                            <option value="8">Provinsi</option>
                            <option value="12">Nasional</option>
                        </select>
                    </div>
                </div>

                <!-- Prestasi 3 (satu baris penuh) -->
                <label>Prestasi 3</label>
                <select name="prestasi3">
                    <option value="0">Tidak Ada</option>
                    <option value="5">Kabupaten</option>
                    <option value="8">Provinsi</option>
                    <option value="12">Nasional</option>
                </select>

                <!-- Tombol hitung prediksi → mengirim data ke hasil.php -->
                <button type="submit" class="btn">
                    Hitung Prediksi →
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