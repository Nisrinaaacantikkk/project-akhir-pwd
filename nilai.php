<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['nama']      = htmlspecialchars(trim($_POST['nama']));
    $_SESSION['akreditasi'] = (int)$_POST['akreditasi'];
    $_SESSION['peringkat']  = (int)$_POST['peringkat'];
    $_SESSION['ranking']    = (int)$_POST['ranking'];
    $_SESSION['eligible']   = (int)$_POST['eligible'];
    $_SESSION['alumni']     = (int)$_POST['alumni'];
} else {
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
    <div class="top-header" style="background-color: #fdd7e3; justify-content:flex-start; margin-top: -15px;">
        <img src="logoupn.png" alt="Logo UPN" >
        <div >
            <h2>Universitas Pembangunan Nasional "Veteran" Yogyakarta</h2>
            <p>Prediksi Peluang Lolos SNBP</p>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="step">
                <div class="active"></div>
                <div class="active"></div>
                <div class="active"></div>
                <div></div>
            </div>

            <h2>Input Nilai Rapor</h2>

            <form action="hasil.php" method="POST">
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

                <label>Semester 5</label>
                <input type="number" step="0.01" name="s5" min="1" max="100" required>

                <h3 style="margin-top:30px;">Prestasi (Opsional)</h3>

                <div class="row">
                    <div>
                        <label>Prestasi 1</label>
                        <select name="prestasi1">
                            <option value="0">Tidak Ada</option>
                            <option value="5">Kabupaten</option>
                            <option value="8">Provinsi</option>
                            <option value="12">Nasional</option>
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

                <label>Prestasi 3</label>
                <select name="prestasi3">
                    <option value="0">Tidak Ada</option>
                    <option value="5">Kabupaten</option>
                    <option value="8">Provinsi</option>
                    <option value="12">Nasional</option>
                </select>

                <button type="submit" class="btn">
                    Hitung Prediksi →
                </button>
            </form>

            <button onclick="history.back()" class="btn-outline">
                ← Kembali
            </button>
        </div>
    </div>
</body>
</html>