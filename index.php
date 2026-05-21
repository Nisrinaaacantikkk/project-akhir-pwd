<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediksi SNBP UPN Veteran Yogyakarta</title>
    <!-- Menghubungkan file CSS untuk tampilan halaman -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- HEADER ATAS
         Menampilkan logo dan nama universitas -->
    <div class="top-header" style="background-color: #fdd7e3; justify-content:flex-start; margin-top: -15px;">
        <img src="logoupn.png" alt="Logo UPN">
        <div>
            <h2>Universitas Pembangunan Nasional "Veteran" Yogyakarta</h2>
            <p>Prediksi Peluang Lolos SNBP</p>
        </div>
    </div>

    <!--  KONTEN UTAMA -->
    <div class="container">
        <div class="card">

            <!-- Judul halaman utama -->
            <h1 class="title">
                Prediksi <span>SNBP</span><br>
                UPN Veteran Yogyakarta
            </h1>

            <!-- Deskripsi singkat aplikasi -->
            <p class="desc">
                Analisis peluang lolos SNBP berdasarkan nilai rapor,
                prestasi, dan data sekolah secara realistis.
            </p>

            <!-- 
                 FORM PILIHAN PROGRAM STUDI
                 Data dikirim ke biodata.php menggunakan metode POST
                 Nilai option berformat "NamaProdi|StandarNilai"
                 Contoh: "Akuntansi|91.55" → nama=Akuntansi, standar=91.55
             -->
            <form action="biodata.php" method="POST">

                <!-- Pilihan Program Studi 1 -->
                <label>PRODI PILIHAN 1</label>
                <select name="prodi1" required>
                    <option value="">-- Pilih Program Studi --</option>
                    <!-- Format value: "NamaProdi|NilaiStandarProdi" -->
                    <option value="Teknik Kimia D3|85.51">Teknik Kimia D3</option>
                    <option value="Agribisnis|88.73">Agribisnis</option>
                    <option value="Agroteknologi|88.67">Agroteknologi</option>
                    <option value="Akuntansi|91.55">Akuntansi</option>
                    <option value="Ekonomi Pembangunan|90">Ekonomi Pembangunan</option>
                    <option value="Hubungan Masyarakat|89.26">Hubungan Masyarakat</option>
                    <option value="Ilmu Administrasi Bisnis|90.72">Ilmu Administrasi Bisnis</option>
                    <option value="Ilmu Hubungan Internasional|89.21">Ilmu Hubungan Internasional</option>
                    <option value="Ilmu Komunikasi|89.21">Ilmu Komunikasi</option>
                    <option value="Ilmu Tanah|86.46">Ilmu Tanah</option>
                    <option value="Manajemen|91.61">Manajemen</option>
                    <option value="Metalurgi|86.97">Metalurgi</option>
                    <option value="Sistem Informasi|88.79">Sistem Informasi</option>
                    <option value="Teknik Geofisika|86.48">Teknik Geofisika</option>
                    <option value="Teknik Geologi|90.11">Teknik Geologi</option>
                    <option value="Teknik Geomatika|86.46">Teknik Geomatika</option>
                    <option value="Teknik Industri|90.42">Teknik Industri</option>
                    <option value="Teknik Informatika|91.41">Teknik Informatika</option>
                    <option value="Teknik Kimia|87.49">Teknik Kimia</option>
                    <option value="Teknik Lingkungan|88.62">Teknik Lingkungan</option>
                    <option value="Teknik Perminyakan|90.79">Teknik Perminyakan</option>
                    <option value="Teknik Pertambangan|91.75">Teknik Pertambangan</option>
                </select>

                <!-- Pilihan Program Studi 2 -->
                <label>PRODI PILIHAN 2</label>
                <select name="prodi2" required>
                    <option value="">-- Pilih Program Studi --</option>
                    <!-- Format value sama: "NamaProdi|NilaiStandarProdi" -->
                    <option value="Teknik Kimia D3|85.51">Teknik Kimia D3</option>
                    <option value="Agribisnis|88.73">Agribisnis</option>
                    <option value="Agroteknologi|88.67">Agroteknologi</option>
                    <option value="Akuntansi|91.55">Akuntansi</option>
                    <option value="Ekonomi Pembangunan|90">Ekonomi Pembangunan</option>
                    <option value="Hubungan Masyarakat|89.26">Hubungan Masyarakat</option>
                    <option value="Ilmu Administrasi Bisnis|90.72">Ilmu Administrasi Bisnis</option>
                    <option value="Ilmu Hubungan Internasional|89.21">Ilmu Hubungan Internasional</option>
                    <option value="Ilmu Komunikasi|89.21">Ilmu Komunikasi</option>
                    <option value="Ilmu Tanah|86.46">Ilmu Tanah</option>
                    <option value="Manajemen|91.61">Manajemen</option>
                    <option value="Metalurgi|86.97">Metalurgi</option>
                    <option value="Sistem Informasi|88.79">Sistem Informasi</option>
                    <option value="Teknik Geofisika|86.48">Teknik Geofisika</option>
                    <option value="Teknik Geologi|90.11">Teknik Geologi</option>
                    <option value="Teknik Geomatika|86.46">Teknik Geomatika</option>
                    <option value="Teknik Industri|90.42">Teknik Industri</option>
                    <option value="Teknik Informatika|91.41">Teknik Informatika</option>
                    <option value="Teknik Kimia|87.49">Teknik Kimia</option>
                    <option value="Teknik Lingkungan|88.62">Teknik Lingkungan</option>
                    <option value="Teknik Perminyakan|90.79">Teknik Perminyakan</option>
                    <option value="Teknik Pertambangan|91.75">Teknik Pertambangan</option>
                </select>

                <!-- Tombol untuk lanjut ke halaman biodata -->
                <button type="submit" class="btn">
                    Mulai Prediksi →
                </button>
            </form>

            <!-- Tombol untuk melihat riwayat prediksi sebelumnya -->
            <a href="riwayat.php" class="btn-outline"
               style="display:block;text-align:center;text-decoration:none;padding:16px;margin-top:15px;
                      border:2px solid #ff6b9d;border-radius:15px;color:#ff4d88;font-weight:bold;">
                Lihat Riwayat Prediksi
            </a>

        </div>
    </div>

</body>
</html>