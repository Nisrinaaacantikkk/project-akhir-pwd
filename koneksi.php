<?php
    $conn = mysqli_connect(
        "localhost",
        "root",
        "",
        "prediksi_snbp"
    );
 
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

?>