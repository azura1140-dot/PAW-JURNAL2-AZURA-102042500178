<?php
require_once 'koneksi.php';
$id = (int)($_GET['id'] ?? 0);
$pesan = '';
if (isset($_POST['hapus'])) {
    try {
        $conn->query("DELETE FROM mata_kuliah WHERE id=$id");
        header("Location: matakuliah_index.php");
    } catch (Exception $e) { $pesan = "Data belum tersimpan. Mata kuliah masih memiliki materi atau tugas. Hapus data terkait terlebih dahulu."; }
}
?>
<!DOCTYPE html><html lang="id"><head><title>Hapus MK</title><link rel="stylesheet" href="style.css"></head><body>
<div class="container">
<h2>Hapus Data?</h2>
<?php if($pesan) echo "<div class='alert'>$pesan</div>"; ?>
<form method="POST">
    <p>Yakin ingin menghapus mata kuliah ini?</p>
    <button type="submit" name="hapus" class="btn" style="background:red;">Ya, Hapus</button>
    <a href="matakuliah_index.php" class="btn btn-secondary" style="text-decoration:none;">Batal</a>
</form></div></body></html>