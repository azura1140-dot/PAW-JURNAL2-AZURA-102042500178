<?php
require_once 'koneksi.php';
$pesan = '';
if (isset($_POST['simpan'])) {
    $kode = $_POST['kode']; $nama = $_POST['nama']; $dosen = $_POST['dosen']; $semester = (int)$_POST['semester'];
    if ($semester < 1 || $semester > 14) { $pesan = "Semester harus 1-14."; } 
    else {
        try {
            $stmt = $conn->prepare("INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $kode, $nama, $dosen, $semester);
            if ($stmt->execute()) header("Location: matakuliah_index.php");
        } catch (Exception $e) { $pesan = "Error: " . $e->getMessage(); }
    }
}
?>
<!DOCTYPE html><html lang="id"><head><title>Tambah MK</title><link rel="stylesheet" href="style.css"></head><body>
<div class="container"><a href="matakuliah_index.php" class="btn-link">&larr; Kembali</a>
<h2>Tambah Mata Kuliah</h2>
<?php if($pesan) echo "<div class='alert'>$pesan</div>"; ?>
<form method="POST">
    <input type="text" name="kode" class="form-control" placeholder="Kode (mis: IF-101)" required><br>
    <input type="text" name="nama" class="form-control" placeholder="Nama Mata Kuliah" required><br>
    <input type="text" name="dosen" class="form-control" placeholder="Dosen Pengampu" required><br>
    <input type="number" name="semester" class="form-control" placeholder="Semester (1-14)" required><br>
    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
</form></div></body></html>