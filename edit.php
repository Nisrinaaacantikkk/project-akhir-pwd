<?php
// Menghubungkan file ini dengan database
require_once 'koneksi.php';

// Mengecek apakah ID tersedia dan berupa angka
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    // Jika tidak valid, kembali ke halaman riwayat
    header("Location: riwayat.php");
    exit();
}

// Mengambil ID dari URL
$id = (int)$_GET['id'];


// PROSES UPDATE DATA
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Mengambil dan membersihkan input nama
    $nama = mysqli_real_escape_string(
        $conn,
        htmlspecialchars(trim($_POST['nama']))
    );

    // Mengambil nilai semester
    $s1 = (float)$_POST['s1'];
    $s2 = (float)$_POST['s2'];
    $s3 = (float)$_POST['s3'];
    $s4 = (float)$_POST['s4'];
    $s5 = (float)$_POST['s5'];

    // Menghitung rata-rata nilai rapor
    $rata = ($s1 + $s2 + $s3 + $s4 + $s5) / 5;

    // Mengambil data tambahan
    $prestasi   = (int)$_POST['prestasi'];
    $akreditasi = (int)$_POST['akreditasi'];
    $peringkat  = (int)$_POST['peringkat'];
    $ranking    = (int)$_POST['ranking'];
    $eligible   = (int)$_POST['eligible'];
    $alumni     = (int)$_POST['alumni'];

    // Menghitung skor ranking paralel
    $skorRanking =
        (($eligible - $ranking) / max($eligible, 1)) * 20;

    // Mengambil standar prodi
    $standar1 = (float)$_POST['standar1'];
    $standar2 = (float)$_POST['standar2'];

    // Mengambil nama prodi
    $prodi1 = mysqli_real_escape_string($conn, $_POST['prodi1']);
    $prodi2 = mysqli_real_escape_string($conn, $_POST['prodi2']);



    // FUNCTION MENGHITUNG PELUANG LOLOS
    function hitungPeluang(
        $rata,
        $prestasi,
        $akreditasi,
        $peringkat,
        $alumni,
        $skorRanking,
        $standar
    ) {

        // Rumus utama peluang SNBP
        $p =
            (
                ($rata * 0.6)
                + $prestasi
                + $akreditasi
                + $peringkat
                + $alumni
                + $skorRanking
            ) / 1.5;

        // Mengurangi nilai berdasarkan standar prodi
        $p -= ($standar - 85) * 1.5;

        // Membatasi hasil antara 0 sampai 100
        return round(
            max(0, min(100, $p)),
            1
        );
    }



    // Menghitung peluang pilihan 1
    $peluang1 = hitungPeluang(
        $rata,
        $prestasi,
        $akreditasi,
        $peringkat,
        $alumni,
        $skorRanking,
        $standar1
    );

    // Menghitung peluang pilihan 2
    $peluang2 = hitungPeluang(
        $rata,
        $prestasi,
        $akreditasi,
        $peringkat,
        $alumni,
        $skorRanking,
        $standar2
    );



    // QUERY UPDATE DATABASE
    $sql = "
        UPDATE riwayat_prediksi SET

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

        WHERE id=$id
    ";



    // Mengecek apakah update berhasil
    if (mysqli_query($conn, $sql)) {

        // Jika berhasil kembali ke halaman riwayat
        header("Location: riwayat.php");
        exit();

    } else {

        // Jika gagal tampilkan error
        $error =
            "Gagal memperbarui data: "
            . mysqli_error($conn);
    }
}



// MENGAMBIL DATA DARI DATABASE
$row = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT * FROM riwayat_prediksi WHERE id = $id"
    )
);


// Jika data tidak ditemukan
if (!$row) {

    // Kembali ke riwayat
    header("Location: riwayat.php");
    exit();
}
?>