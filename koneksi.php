<?php
    /**
     * koneksi.php
     * File ini bertugas menghubungkan aplikasi ke database MySQL.
     * Di-include oleh semua file yang membutuhkan akses database.
     */
 
    // Membuat koneksi ke database MySQL menggunakan MySQLi
    // Parameter: host, username, password, nama_database
    $conn = mysqli_connect(
        "localhost",   // Host database (server lokal)
        "root",        // Username database
        "",            // Password database (kosong untuk XAMPP default)
        "prediksi_snbp" // Nama database yang digunakan
    );
 
    // Cek apakah koneksi berhasil
    // Jika gagal, hentikan program dan tampilkan pesan error
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
?>