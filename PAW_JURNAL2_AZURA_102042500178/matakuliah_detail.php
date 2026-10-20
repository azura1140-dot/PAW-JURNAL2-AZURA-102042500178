<?php
require_once 'koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$data = $conn->query("SELECT * FROM mata_kuliah WHERE id=$id")->fetch_assoc();
if (!$data) die("Data tidak ditemukan.");
?>
<!DOCTYPE html><html lang="id"><head><title>Detail MK</title><link rel="stylesheet" href="style.css"></head><body>
<div class="main-content">
    <h1>Detail Mata Kuliah</h1>
    <div class="content-card">
        <p><b>Kode:</b> <?= htmlspecialchars($data['kode']) ?></p>
        <p><b>Nama:</b> <?= htmlspecialchars($data['nama']) ?></p>
        <p><b>Dosen:</b> <?= htmlspecialchars($data['dosen']) ?></p>
        <p><b>Semester:</b> <?= $data['semester'] ?></p>
    </div>
    <br><a href="matakuliah_index.php" class="btn btn-secondary" style="text-decoration:none;">Kembali</a>
</div></body></html>