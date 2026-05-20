<?php
<<<<<<< HEAD
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prodi1 = explode("|", $_POST['prodi1']);
    $prodi2 = explode("|", $_POST['prodi2']);

    $_SESSION['nama_prodi1']   = $prodi1[0];
    $_SESSION['standar_prodi1'] = (float)$prodi1[1];

    $_SESSION['nama_prodi2']   = $prodi2[0];
    $_SESSION['standar_prodi2'] = (float)$prodi2[1];
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
    <div class="top-header" style="background-color: #fdd7e3; justify-content:flex-start; margin-top: -15px;">
        <img src="logoupn.png" alt="Logo UPN" >
        <div >
            <h2>Universitas Pembangunan Nasional "Veteran" Yogyakarta</h2>
=======
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
>>>>>>> 7571be16df99208c22d146bdab72c1bea6232a6a
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

<<<<<<< HEAD
            <h2>Input Biodata &amp; Sekolah</h2>
=======
            <h2>Input Biodata & Sekolah</h2>
>>>>>>> 7571be16df99208c22d146bdab72c1bea6232a6a

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
<<<<<<< HEAD
                    <option value="10">1 – 500</option>
                    <option value="8">501 – 1000</option>
=======
                    <option value="10">1 - 500</option>
                    <option value="8">501 - 1000</option>
>>>>>>> 7571be16df99208c22d146bdab72c1bea6232a6a
                    <option value="5">1000+</option>
                </select>

                <div class="row">
                    <div>
                        <label>Ranking Paralel</label>
<<<<<<< HEAD
                        <input type="number" name="ranking" min="1" required>
                    </div>
=======
                        <input type="number" name="ranking" min="0" required>
                    </div>

>>>>>>> 7571be16df99208c22d146bdab72c1bea6232a6a
                    <div>
                        <label>Jumlah Eligible</label>
                        <input type="number" name="eligible" min="1" required>
                    </div>
                </div>

                <label>Jumlah Alumni Lolos SNBP</label>
                <input type="number" name="alumni" min="0" required>
<<<<<<< HEAD

                <button type="submit" class="btn">
                    Lanjut ke Input Nilai →
                </button>
=======
                <button type="submit" class="btn">
                    Lanjut ke Input Nilai →
                </button>

>>>>>>> 7571be16df99208c22d146bdab72c1bea6232a6a
            </form>

            <button onclick="history.back()" class="btn-outline">
                ← Kembali
            </button>
        </div>
    </div>
</body>
</html>