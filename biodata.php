<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $prodi1 = explode("|", $_POST['prodi1']);
        $prodi2 = explode("|", $_POST['prodi2']);

        $_SESSION['nama_prodi1'] = $prodi1[0];
        $_SESSION['standar_prodi1'] = $prodi1[1];

        $_SESSION['nama_prodi2'] = $prodi2[0];
        $_SESSION['standar_prodi2'] = $prodi2[1];
    } else {
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
    <div class="top-header">
        <img src="logoupn.png" alt="Logo UPN">
        <div>
            <h2>Universitas Pembangunan Nasional “Veteran” Yogyakarta</h2>
            <p>Prediksi Peluang Lolos SNBP</p>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="step">
                <div class="active"></div>
                <div class="active"></div>
                <div></div>
                <div></div>
            </div>

            <h2>Input Biodata & Sekolah</h2>

            <form action="nilai.php" method="POST">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required>

                <label>Akreditasi Sekolah</label>
                <select name="akreditasi" required>
                    <option value="12">A</option>
                    <option value="8">B</option>
                    <option value="4">C</option>
                </select>

                <label>Peringkat Sekolah Nasional</label>
                <select name="peringkat" required>
                    <option value="10">1 - 500</option>
                    <option value="8">501 - 1000</option>
                    <option value="5">1000+</option>
                </select>

                <div class="row">
                    <div>
                        <label>Ranking Paralel</label>
                        <input type="number" name="ranking" min="0" required>
                    </div>

                    <div>
                        <label>Jumlah Eligible</label>
                        <input type="number" name="eligible" min="1" required>
                    </div>
                </div>

                <label>Jumlah Alumni Lolos SNBP</label>
                <input type="number" name="alumni" min="0" required>
                <button type="submit" class="btn">
                    Lanjut ke Input Nilai →
                </button>

            </form>

            <button onclick="history.back()" class="btn-outline">
                ← Kembali
            </button>
        </div>
    </div>
</body>
</html>