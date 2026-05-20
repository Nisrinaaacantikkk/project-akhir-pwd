<?php
    require_once 'koneksi.php';

    //hapus 1 riwayat
    if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
        $id = (int)$_GET['hapus'];
        mysqli_query($conn, "DELETE FROM riwayat_prediksi WHERE id = $id");
        header("Location: riwayat.php");
        exit();
    }

    //ambil semua database - diurutkan dari yang PALING LAMA ke PALING BARU
    $result = mysqli_query($conn,
        "SELECT * FROM riwayat_prediksi ORDER BY tanggal ASC");

    // Fungsi status (READ display)
    function statusPeluang($nilai) {
        if ($nilai >= 75)   return ["PELUANG BESAR",  "status-besar"];
        elseif ($nilai >= 50) return ["PELUANG SEDANG", "status-sedang"];
        else                  return ["PELUANG KECIL",  "status-kecil"];
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Prediksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-header" style="background-color: #fdd7e3; justify-content:flex-start; margin-top: -15px;">
        <img src="logoupn.png" alt="Logo UPN" >
        <div >
            <h2>Universitas Pembangunan Nasional "Veteran" Yogyakarta</h2>
            <p>Prediksi Peluang Lolos SNBP</p>
        </div>
    </div>

    <div class="container" style="flex-direction:column;align-items:center;padding:30px 20px;">
        <div style="width:100%;max-width:1000px;">

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
                <h2 style="color:#1d2140;">📋 Riwayat Prediksi</h2>
                <a href="index.php" class="btn" style="text-decoration:none;padding:12px 24px;width:auto;">
                    + Prediksi Baru
                </a>
            </div>

            <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="empty-state">
                <p>Belum ada riwayat prediksi.</p>
                <a href="index.php" class="btn" style="text-decoration:none;display:inline-block;margin-top:15px;padding:14px 28px;width:auto;">
                    Mulai Prediksi
                </a>
            </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="riwayat-table">
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
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)):
                                [$status1, $class1] = statusPeluang($row['peluang1']);
                                [$status2, $class2] = statusPeluang($row['peluang2']);
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><b><?= htmlspecialchars($row['nama']) ?></b></td>
                                <td><?= round($row['rata_rapor'], 2) ?></td>
                                <td><?= htmlspecialchars($row['prodi1']) ?></td>
                                <td>
                                    <span class="badge <?= $class1 ?>">
                                        <?= $row['peluang1'] ?>%
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['prodi2']) ?></td>
                                <td>
                                    <span class="badge <?= $class2 ?>">
                                        <?= $row['peluang2'] ?>%
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                                    <a href="riwayat.php?hapus=<?= $row['id'] ?>"
                                       class="btn-hapus"
                                       onclick="return confirm('Hapus riwayat ini?')">Hapus</a>
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