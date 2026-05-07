<!DOCTYPE html>
<html>
<head>
<title>Input Data</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<div class="card">

<div class="title">Input Data SNBP</div>

<form action="proses.php" method="POST">

<input type="text" name="nama" placeholder="Nama" required>

<input type="number" name="smt1" placeholder="Semester 1" required>
<input type="number" name="smt2" placeholder="Semester 2" required>
<input type="number" name="smt3" placeholder="Semester 3" required>
<input type="number" name="smt4" placeholder="Semester 4" required>
<input type="number" name="smt5" placeholder="Semester 5" required>

<input type="number" name="peringkat" placeholder="Peringkat" required>
<input type="number" name="total_siswa" placeholder="Total siswa" required>

<select name="prodi">
<option>Teknik Informatika</option>
<option>Manajemen</option>
<option>Akuntansi</option>
</select>

<select name="ranking_sekolah">
<option value="1-500">1-500</option>
<option value="501-1000">501-1000</option>
<option value=">1000">>1000</option>
</select>

<input type="number" name="alumni" placeholder="Jumlah Alumni">

<select name="prestasi">
<option value="tidak">Tidak ada</option>
<option value="kabupaten">Kabupaten</option>
<option value="provinsi">Provinsi</option>
<option value="nasional">Nasional</option>
</select>

<button type="submit">Hitung</button>

</form>

</div>
</div>

</body>
</html>