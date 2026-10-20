<?php
require 'koneksi.php';
$pesan = '';

if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $dosen = $_POST['dosen'];
    $semester = (int)$_POST['semester'];
    
    if ($semester < 1 || $semester > 14) {
        $pesan = "Semester harus bilangan bulat 1-14.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $kode, $nama, $dosen, $semester);
            if ($stmt->execute()) {
                $pesan = "Mata kuliah berhasil ditambahkan.";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $pesan = "Kode mata kuliah sudah digunakan.";
            } else {
                $pesan = "Error: " . $e->getMessage();
            }
        }
    }
}

if (isset($_POST['hapus'])) {
    $id_hapus = (int)$_POST['id_hapus'];
    try {
        $stmt = $conn->prepare("DELETE FROM mata_kuliah WHERE id = ?");
        $stmt->bind_param("i", $id_hapus);
        $stmt->execute();
        $pesan = "Data berhasil dihapus.";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            $pesan = "Data belum tersimpan. Mata kuliah masih memiliki materi atau tugas. Hapus data terkait terlebih dahulu.";
        }
    }
}

$search_query = "";
$search_param = "%";
if (isset($_GET['cari'])) {
    $search_param = "%" . $_GET['keyword'] . "%";
    $search_query = "WHERE kode LIKE ? OR nama LIKE ?";
}

$stmt = $conn->prepare("SELECT id, kode, nama, dosen, semester FROM mata_kuliah $search_query ORDER BY nama");
if (isset($_GET['cari'])) {
    $stmt->bind_param("ss", $search_param, $search_param);
}
$stmt->execute();
$hasil = $stmt->get_result();
$total_data = $hasil->num_rows;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mata Kuliah - LMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn-link">&larr; Kembali ke Beranda</a>
        <h1>Mata kuliah</h1>
        
        <?php if($pesan): ?>
            <div class="<?= strpos($pesan, 'berhasil') !== false ? 'alert-success' : 'alert' ?>">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <div class="flex-between">
            <p>Menampilkan <?= $total_data ?> data.</p>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="keyword" placeholder="Cari kode atau nama..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>" style="width: 250px;">
            <button type="submit" name="cari" class="btn btn-primary">Cari</button>
            <a href="mata_kuliah.php" class="btn btn-secondary" style="text-decoration:none;">Reset</a>
        </form>

        <table>
            <tr>
                <th>Mata kuliah</th>
                <th>Dosen / semester</th>
                <th>Aksi</th>
            </tr>
            <?php if ($total_data > 0): ?>
                <?php while ($row = $hasil->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['kode']) ?></strong> - <?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['dosen']) ?> / Semester <?= $row['semester'] ?></td>
                    <td>
                        <a href="edit_mk.php?id=<?= $row['id'] ?>" class="btn-link">Edit</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus <?= htmlspecialchars($row['nama']) ?>? 
Data baru dihapus setelah tombol konfirmasi dikirim.');">
                            <input type="hidden" name="id_hapus" value="<?= $row['id'] ?>">
                            <button type="submit" name="hapus" class="btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" style="text-align:center;">Data tidak ditemukan.</td></tr>
            <?php endif; ?>
        </table>

        <hr style="margin: 40px 0;">
        <h2>Tambah Mata kuliah</h2>
        <form method="POST" style="max-width: 500px;">
            <div class="form-group">
                <label>Kode mata kuliah (3-20 Karakter)</label>
                <input type="text" name="kode" class="form-control" minlength="3" maxlength="20" required pattern="[A-Z0-9\-]+">
            </div>
            <div class="form-group">
                <label>Nama mata kuliah (3-100 Karakter)</label>
                <input type="text" name="nama" class="form-control" minlength="3" maxlength="100" required>
            </div>
            <div class="form-group">
                <label>Dosen pengampu (3-100 Karakter)</label>
                <input type="text" name="dosen" class="form-control" minlength="3" maxlength="100" required>
            </div>
            <div class="form-group">
                <label>Semester (1-14)</label>
                <input type="number" name="semester" class="form-control" min="1" max="14" required>
            </div>
            <button type="submit" name="tambah" class="btn btn-primary">Simpan data</button>
            <button type="reset" class="btn btn-secondary">Batal</button>
        </form>
    </div>
</body>
</html>