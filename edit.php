<?php
require_once 'koneksi.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: riwayat.php");
    exit();
}

$id = (int)$_GET['id'];

// =============================================
// UPDATE — Proses form edit
// =============================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['nama'])));
    $s1       = (float)$_POST['s1'];
    $s2       = (float)$_POST['s2'];
    $s3       = (float)$_POST['s3'];
    $s4       = (float)$_POST['s4'];
    $s5       = (float)$_POST['s5'];
    $rata     = ($s1 + $s2 + $s3 + $s4 + $s5) / 5;
    $prestasi = (int)$_POST['prestasi'];
    $akreditasi = (int)$_POST['akreditasi'];
    $peringkat  = (int)$_POST['peringkat'];
    $ranking    = (int)$_POST['ranking'];
    $eligible   = (int)$_POST['eligible'];
    $alumni     = (int)$_POST['alumni'];

    // Hitung ulang peluang
    $skorRanking = (($eligible - $ranking) / max($eligible, 1)) * 20;

    $standar1 = (float)$_POST['standar1'];
    $standar2 = (float)$_POST['standar2'];
    $prodi1   = mysqli_real_escape_string($conn, $_POST['prodi1']);
    $prodi2   = mysqli_real_escape_string($conn, $_POST['prodi2']);

    function hitungPeluang($rata, $prestasi, $akreditasi, $peringkat,
                            $alumni, $skorRanking, $standar) {
        $p  = (($rata * 0.6) + $prestasi + $akreditasi
               + $peringkat + $alumni + $skorRanking) / 1.5;
        $p -= ($standar - 85) * 1.5;
        return round(max(0, min(100, $p)), 1);
    }

    $peluang1 = hitungPeluang($rata, $prestasi, $akreditasi, $peringkat, $alumni, $skorRanking, $standar1);
    $peluang2 = hitungPeluang($rata, $prestasi, $akreditasi, $peringkat, $alumni, $skorRanking, $standar2);

    $sql = "UPDATE riwayat_prediksi SET
                nama='$nama',
                akreditasi=$akreditasi,
                peringkat=$peringkat,
                ranking=$ranking,
                eligible=$eligible,
                alumni=$alumni,
                rata_rapor=$rata,
                prestasi=$prestasi,
                prodi1='$prodi1',
                standar1=$standar1,
                peluang1=$peluang1,
                prodi2='$prodi2',
                standar2=$standar2,
                peluang2=$peluang2
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: riwayat.php");
        exit();
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($conn);
    }
}

// =============================================
// READ — Ambil data untuk ditampilkan di form
// =============================================
$row = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM riwayat_prediksi WHERE id = $id")
);

if (!$row) {
    header("Location: riwayat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Riwayat</title>
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

    <div class="container">
        <div class="card" style="width:600px;max-width:100%;">
            <h2>✏️ Edit Riwayat Prediksi</h2>
            <p style="color:#888;margin-bottom:20px;">ID Riwayat: #<?= $id ?></p>

            <?php if (isset($error)): ?>
                <div style="background:#ffe0e0;color:#c00;padding:12px;border-radius:10px;margin-bottom:15px;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>

                <div class="row">
                    <div>
                        <label>Semester 1</label>
                        <input type="number" step="0.01" name="s1" min="1" max="100"
                               value="<?= $row['rata_rapor'] ?>" required>
                    </div>
                    <div>
                        <label>Semester 2</label>
                        <input type="number" step="0.01" name="s2" min="1" max="100"
                               value="<?= $row['rata_rapor'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label>Semester 3</label>
                        <input type="number" step="0.01" name="s3" min="1" max="100"
                               value="<?= $row['rata_rapor'] ?>" required>
                    </div>
                    <div>
                        <label>Semester 4</label>
                        <input type="number" step="0.01" name="s4" min="1" max="100"
                               value="<?= $row['rata_rapor'] ?>" required>
                    </div>
                </div>
                <label>Semester 5</label>
                <input type="number" step="0.01" name="s5" min="1" max="100"
                       value="<?= $row['rata_rapor'] ?>" required>

                <label>Total Poin Prestasi</label>
                <input type="number" name="prestasi" min="0" max="36"
                       value="<?= $row['prestasi'] ?>" required>

                <label>Akreditasi Sekolah</label>
                <select name="akreditasi">
                    <option value="12" <?= $row['akreditasi']==12?'selected':'' ?>>A</option>
                    <option value="8"  <?= $row['akreditasi']==8 ?'selected':'' ?>>B</option>
                    <option value="4"  <?= $row['akreditasi']==4 ?'selected':'' ?>>C</option>
                </select>

                <label>Peringkat Nasional Sekolah</label>
                <select name="peringkat">
                    <option value="10" <?= $row['peringkat']==10?'selected':'' ?>>1 – 500</option>
                    <option value="8"  <?= $row['peringkat']==8 ?'selected':'' ?>>501 – 1000</option>
                    <option value="5"  <?= $row['peringkat']==5 ?'selected':'' ?>>1000+</option>
                </select>

                <div class="row">
                    <div>
                        <label>Ranking Paralel</label>
                        <input type="number" name="ranking" min="1"
                               value="<?= $row['ranking'] ?>" required>
                    </div>
                    <div>
                        <label>Jumlah Eligible</label>
                        <input type="number" name="eligible" min="1"
                               value="<?= $row['eligible'] ?>" required>
                    </div>
                </div>

                <label>Alumni Lolos SNBP</label>
                <input type="number" name="alumni" min="0"
                       value="<?= $row['alumni'] ?>" required>

                <!-- Hidden: prodi dan standar tidak diubah di form ini -->
                <input type="hidden" name="prodi1"   value="<?= htmlspecialchars($row['prodi1']) ?>">
                <input type="hidden" name="standar1"  value="<?= $row['standar1'] ?>">
                <input type="hidden" name="prodi2"   value="<?= htmlspecialchars($row['prodi2']) ?>">
                <input type="hidden" name="standar2"  value="<?= $row['standar2'] ?>">

                <button type="submit" class="btn">💾 Simpan Perubahan</button>
            </form>

            <a href="riwayat.php" class="btn-outline"
               style="display:block;text-align:center;text-decoration:none;padding:16px;margin-top:15px;">
                ← Batal, Kembali ke Riwayat
            </a>
        </div>
    </div>
</body>
</html>