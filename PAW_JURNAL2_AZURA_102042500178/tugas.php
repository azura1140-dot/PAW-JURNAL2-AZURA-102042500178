<?php
require 'koneksi.php';
$pesan = '';

if (isset($_POST['tambah'])) {
    $mk_id = (int)$_POST['mata_kuliah_id'];
    $judul = $_POST['judul'];
    $instruksi = $_POST['instruksi'];
    $tenggat = $_POST['tenggat'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("INSERT INTO tugas (mata_kuliah_id, judul, instruksi, tenggat, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $mk_id, $judul, $instruksi, $tenggat, $status);
    if($stmt->execute()) {
        $pesan = "Tugas berhasil ditambahkan.";
    }
}

if (isset($_POST['hapus'])) {
    $id_hapus = (int)$_POST['id_hapus'];
    $stmt = $conn->prepare("DELETE FROM tugas WHERE id = ?");
    $stmt->bind_param("i", $id_hapus);
    if($stmt->execute()) {
        $pesan = "Tugas berhasil dihapus.";
    }
}

$search_query = "";
$params = [];
$types = "";

if (isset($_GET['cari'])) {
    if (!empty($_GET['keyword'])) {
        $search_query .= " AND t.judul LIKE ?";
        $params[] = "%" . $_GET['keyword'] . "%";
        $types .= "s";
    }
    if (!empty($_GET['filter_mk'])) {
        $search_query .= " AND t.mata_kuliah_id = ?";
        $params[] = (int)$_GET['filter_mk'];
        $types .= "i";
    }
}

$query = "SELECT t.id, t.judul, t.tenggat, t.status, mk.nama as mk_nama 
          FROM tugas t 
          JOIN mata_kuliah mk ON t.mata_kuliah_id = mk.id 
          WHERE 1=1 $search_query 
          ORDER BY t.judul";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$hasil = $stmt->get_result();
$total_data = $hasil->num_rows;

$mk_list = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas - LMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn-link">&larr; Kembali ke Beranda</a>
        <h1>Tugas</h1>
        
        <?php if($pesan): ?>
            <div class="<?= strpos($pesan, 'berhasil') !== false ? 'alert-success' : 'alert' ?>">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <div class="flex-between">
            <p>Menampilkan <?= $total_data ?> data.</p>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="keyword" placeholder="Cari judul..." value="<?= htmlspecialchars($_GET['keyword']??'') ?>">
            <select name="filter_mk">
                <option value="">Semua mata kuliah</option>
                <?php foreach($mk_list as $mk): ?>
                <option value="<?= $mk['id'] ?>" <?= (isset($_GET['filter_mk']) && $_GET['filter_mk']==$mk['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($mk['nama']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="cari" class="btn btn-primary">Cari</button>
            <a href="tugas.php" class="btn btn-secondary" style="text-decoration:none;">Reset</a>
        </form>

        <table>
            <tr>
                <th>Judul tugas</th>
                <th>Mata kuliah</th>
                <th>Tenggat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php if($total_data > 0): while ($row = $hasil->fetch_assoc()): ?>
            <tr>
                <td><strong><?= htmlspecialchars($row['judul']) ?></strong></td>
                <td><?= htmlspecialchars($row['mk_nama']) ?></td>
                <td><?= date('d/m/Y', strtotime($row['tenggat'])) ?></td>
                <td>
                    <span class="status-<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span>
                </td>
                <td>
                    <a href="detail_tugas.php?id=<?= $row['id'] ?>" class="btn-link">Detail</a>
                    <a href="edit_tugas.php?id=<?= $row['id'] ?>" class="btn-link">Edit</a>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus <?= htmlspecialchars($row['judul']) ?>?');">
                        <input type="hidden" name="id_hapus" value="<?= $row['id'] ?>">
                        <button type="submit" name="hapus" class="btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="5" style="text-align:center;">Data tidak ditemukan.</td></tr>
            <?php endif; ?>
        </table>

        <hr style="margin: 40px 0;">
        <h2>Tambah Tugas</h2>
        <form method="POST" style="max-width: 500px;">
            <div class="form-group">
                <label>Mata kuliah</label>
                <select name="mata_kuliah_id" class="form-control" required>
                    <option value="">Pilih Mata kuliah</option>
                    <?php 
                    $mk_list->data_seek(0);
                    while($mk = $mk_list->fetch_assoc()): 
                    ?>
                    <option value="<?= $mk['id'] ?>"><?= htmlspecialchars($mk['nama']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Judul tugas</label>
                <input type="text" name="judul" class="form-control" minlength="3" maxlength="150" required>
            </div>
            <div class="form-group">
                <label>Instruksi tugas</label>
                <textarea name="instruksi" class="form-control" minlength="3" maxlength="2000" required rows="4"></textarea>
            </div>
            <div class="form-group">
                <label>Tenggat</label>
                <input type="date" name="tenggat" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="Draf">Draf</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
            <button type="submit" name="tambah" class="btn btn-primary">Simpan data</button>
            <button type="reset" class="btn btn-secondary">Batal</button>
        </form>
    </div>
</body>
</html>