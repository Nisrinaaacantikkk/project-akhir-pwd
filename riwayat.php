<?php
    // Memanggil file koneksi database
    require_once 'koneksi.php';

    // Fitur hapus data riwayat
    // Mengecek apakah ada parameter hapus pada URL
    // dan memastikan nilainya berupa angka
    if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {

        // Mengambil ID data yang akan dihapus
        $id = (int)$_GET['hapus'];

        // Query menghapus data berdasarkan ID
        mysqli_query($conn, "DELETE FROM riwayat_prediksi WHERE id = $id");

        // Redirect kembali ke halaman riwayat
        header("Location: riwayat.php");
        exit();
    }

    // Mengambil seluruh data riwayat prediksi
    // Diurutkan berdasarkan tanggal dari paling lama ke terbaru
    $result = mysqli_query($conn,
        "SELECT * FROM riwayat_prediksi ORDER BY tanggal ASC");

    // Fungsi menentukan status peluang
    function statusPeluang($nilai) {

        // Jika nilai di atas atau sama dengan 75
        if ($nilai >= 75)
            return ["PELUANG BESAR", "status-besar"];

        // Jika nilai di atas atau sama dengan 50
        elseif ($nilai >= 50)
            return ["PELUANG SEDANG", "status-sedang"];

        // Jika nilai di bawah 50
        else
            return ["PELUANG KECIL", "status-kecil"];
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <!-- Pengaturan karakter dan responsive -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman -->
    <title>Riwayat Prediksi</title>

    <!-- Menghubungkan file CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Header website -->
    <div class="top-header"
         style="background-color: #fdd7e3;
                justify-content:flex-start;
                margin-top: -15px;">

        <!-- Logo -->
        <img src="logoupn.png" alt="Logo UPN">

        <!-- Judul website -->
        <div>
            <h2>Universitas Pembangunan Nasional "Veteran" Yogyakarta</h2>
            <p>Prediksi Peluang Lolos SNBP</p>
        </div>
    </div>

    <!-- Container utama -->
    <div class="container"
         style="flex-direction:column;
                align-items:center;
                padding:30px 20px;">

        <div style="width:100%;max-width:1000px;">

            <!-- Judul halaman dan tombol tambah -->
            <div style="display:flex;
                        justify-content:space-between;
                        align-items:center;
                        margin-bottom:24px;">

                <h2 style="color:#1d2140;">
                    📋 Riwayat Prediksi
                </h2>

                <!-- Tombol prediksi baru -->
                <a href="index.php"
                   class="btn"
                   style="text-decoration:none;
                          padding:12px 24px;
                          width:auto;">

                    + Prediksi Baru
                </a>
            </div>

            <!-- Mengecek apakah data riwayat kosong -->
            <?php if (mysqli_num_rows($result) == 0): ?>

            <!-- Tampilan jika belum ada data -->
            <div class="empty-state">

                <p>Belum ada riwayat prediksi.</p>

                <!-- Tombol mulai prediksi -->
                <a href="index.php"
                   class="btn"
                   style="text-decoration:none;
                          display:inline-block;
                          margin-top:15px;
                          padding:14px 28px;
                          width:auto;">

                    Mulai Prediksi
                </a>
            </div>

            <?php else: ?>

                <!-- Tabel riwayat prediksi -->
                <div class="table-wrapper">

                    <table class="riwayat-table">

                        <!-- Kepala tabel -->
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Rata Rapor</th>
                                <th>Prodi 1</th>
                                <th>Peluang 1</th>
                                <th>Prodi 2</th>
                                <th>Peluang 2</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <!-- Isi tabel -->
                        <tbody>

                            <?php
                            // Nomor urut
                            $no = 1;

                            // Menampilkan seluruh data
                            while ($row = mysqli_fetch_assoc($result)):

                                // Menentukan status peluang prodi 1
                                [$status1, $class1] =
                                    statusPeluang($row['peluang1']);

                                // Menentukan status peluang prodi 2
                                [$status2, $class2] =
                                    statusPeluang($row['peluang2']);
                            ?>

                            <tr>

                                <!-- Nomor -->
                                <td><?= $no++ ?></td>

                                <!-- Nama -->
                                <td>
                                    <b>
                                        <?= htmlspecialchars($row['nama']) ?>
                                    </b>
                                </td>

                                <!-- Rata-rata rapor -->
                                <td>
                                    <?= round($row['rata_rapor'], 2) ?>
                                </td>

                                <!-- Prodi pilihan 1 -->
                                <td>
                                    <?= htmlspecialchars($row['prodi1']) ?>
                                </td>

                                <!-- Peluang pilihan 1 -->
                                <td>
                                    <span class="badge <?= $class1 ?>">
                                        <?= $row['peluang1'] ?>%
                                    </span>
                                </td>

                                <!-- Prodi pilihan 2 -->
                                <td>
                                    <?= htmlspecialchars($row['prodi2']) ?>
                                </td>

                                <!-- Peluang pilihan 2 -->
                                <td>
                                    <span class="badge <?= $class2 ?>">
                                        <?= $row['peluang2'] ?>%
                                    </span>
                                </td>

                                <!-- Tanggal prediksi -->
                                <td>
                                    <?= date('d/m/Y H:i',
                                        strtotime($row['tanggal'])) ?>
                                </td>

                                <!-- Tombol aksi -->
                                <td>

                                    <!-- Tombol edit -->
                                    <a href="edit.php?id=<?= $row['id'] ?>"
                                       class="btn-edit">
                                        Edit
                                    </a>

                                    <!-- Tombol hapus -->
                                    <a href="riwayat.php?hapus=<?= $row['id'] ?>"
                                       class="btn-hapus"
                                       onclick="return confirm('Hapus riwayat ini?')">

                                        Hapus
                                    </a>
                                </td>
                            </tr>

                            <?php endwhile; ?>

                        </tbody>
                    </table>
                </div>

            <?php endif; ?>
        </div>
    </div>
</body>
</html>