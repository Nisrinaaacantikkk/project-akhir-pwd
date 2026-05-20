<?php
    $conn = mysqli_connect(
        "localhost",
        "root",
        "",
        "prediksi_snbp"
    );
<<<<<<< HEAD
 
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

=======

    if(!$conn){
        die("Koneksi gagal: " . mysqli_connect_error());
    }
>>>>>>> 7571be16df99208c22d146bdab72c1bea6232a6a
?>