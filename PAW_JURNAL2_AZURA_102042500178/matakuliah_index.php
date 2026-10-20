<?php
require_once 'koneksi.php';
$search_query = ""; $search_param = "%";
if (isset($_GET['cari'])) {
    $search_param = "%" . $_GET['keyword'] . "%";
    $search_query = "WHERE kode LIKE ? OR nama LIKE ?";
}
$stmt = $conn->prepare("SELECT * FROM mata_kuliah $search_query ORDER BY nama");
if (isset($_GET['cari'])) $stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$hasil = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id"><head><title>Mata Kuliah</title><link rel="stylesheet" href="style.css"></head><body>
<div class="container">
    <a href="index.php" class="btn-link">&larr; Beranda</a>
    <h1>Daftar Mata Kuliah</h1>
    <a href="matakuliah_tambah.php" class="btn btn-primary" style="margin-bottom:15px; display:inline-block; text-decoration:none;">+ Tambah Mata Kuliah</a>
    
    <form method="GET" class="search-box">
        <input type="text" name="keyword" placeholder="Cari..." value="<?= htmlspecialchars($_GET['keyword']??'') ?>">
        <button type="submit" name="cari" class="btn btn-primary">Cari</button>
        <a href="matakuliah_index.php" class="btn btn-secondary" style="text-decoration:none;">Reset</a>
    </form>
    <table>
        <tr><th>Mata kuliah</th><th>Dosen / semester</th><th>Aksi</th></tr>
        <?php if($hasil->num_rows > 0): while($row = $hasil->fetch_assoc()): ?>
        <tr>
            <td><strong><?= htmlspecialchars($row['kode']) ?></strong> - <?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['dosen']) ?> / Sem <?= $row['semester'] ?></td>
            <td>
                <a href="matakuliah_detail.php?id=<?= $row['id'] ?>" class="btn-link">Detail</a>
                <a href="matakuliah_edit.php?id=<?= $row['id'] ?>" class="btn-link">Edit</a>
                <a href="matakuliah_hapus.php?id=<?= $row['id'] ?>" class="btn-link" style="color:red;">Hapus</a>
            </td>
        </tr>
        <?php endwhile; else: ?><tr><td colspan="3">Tidak ada data.</td></tr><?php endif; ?>
    </table>
</div></body></html>