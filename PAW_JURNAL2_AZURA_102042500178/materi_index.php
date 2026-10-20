<?php
require_once 'koneksi.php';
$q = "SELECT m.*, mk.nama as mk_nama FROM materi m JOIN mata_kuliah mk ON m.mata_kuliah_id = mk.id";
$hasil = $conn->query($q);
?>
<!DOCTYPE html><html lang="id"><head><title>Materi</title><link rel="stylesheet" href="style.css"></head><body>
<div class="container"><a href="index.php" class="btn-link">&larr; Beranda</a>
<h1>Daftar Materi</h1>
<a href="materi_tambah.php" class="btn btn-primary" style="margin-bottom:15px; display:inline-block; text-decoration:none;">+ Tambah Materi</a>
<table>
    <tr><th>Judul</th><th>Mata Kuliah</th><th>Aksi</th></tr>
    <?php while($row = $hasil->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['judul']) ?></td>
        <td><?= htmlspecialchars($row['mk_nama']) ?></td>
        <td>
            <a href="materi_detail.php?id=<?= $row['id'] ?>" class="btn-link">Detail</a>
            <a href="materi_edit.php?id=<?= $row['id'] ?>" class="btn-link">Edit</a>
            <a href="materi_hapus.php?id=<?= $row['id'] ?>" class="btn-link" style="color:red;">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table></div></body></html>