<?php
require_once 'koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$data = $conn->query("SELECT * FROM mata_kuliah WHERE id=$id")->fetch_assoc();
if (!$data) die("Data tidak ditemukan.");

$pesan = '';
if (isset($_POST['simpan'])) {
    $kode = $_POST['kode']; $nama = $_POST['nama']; $dosen = $_POST['dosen']; $semester = (int)$_POST['semester'];
    $stmt = $conn->prepare("UPDATE mata_kuliah SET kode=?, nama=?, dosen=?, semester=? WHERE id=?");
    $stmt->bind_param("sssii", $kode, $nama, $dosen, $semester, $id);
    if ($stmt->execute()) header("Location: matakuliah_index.php");
}
?>
<!DOCTYPE html><html lang="id"><head><title>Edit MK</title><link rel="stylesheet" href="style.css"></head><body>
<div class="container"><a href="matakuliah_index.php" class="btn-link">&larr; Kembali</a>
<h2>Edit Mata Kuliah</h2>
<form method="POST">
    <input type="text" name="kode" class="form-control" value="<?= htmlspecialchars($data['kode']) ?>" required><br>
    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required><br>
    <input type="text" name="dosen" class="form-control" value="<?= htmlspecialchars($data['dosen']) ?>" required><br>
    <input type="number" name="semester" class="form-control" value="<?= $data['semester'] ?>" required><br>
    <button type="submit" name="simpan" class="btn btn-primary">Update</button>
</form></div></body></html>