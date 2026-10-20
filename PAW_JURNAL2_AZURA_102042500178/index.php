<?php
require 'koneksi.php';
function hitungData($conn, $tabel) {
    $stmt = $conn->prepare("SELECT COUNT(id) as total FROM $tabel");
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Learning Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 800px;">
        <h1>Learning Management System</h1>
        <p>Pengelolaan mata kuliah, materi pembelajaran, dan tugas.</p>
        <div class="summary">
            <div class="card"><h2><?= hitungData($conn, 'mata_kuliah') ?></h2><p>Mata kuliah</p></div>
            <div class="card"><h2><?= hitungData($conn, 'materi') ?></h2><p>Materi</p></div>
            <div class="card"><h2><?= hitungData($conn, 'tugas') ?></h2><p>Tugas</p></div>
        </div>
        <div class="menu">
            <a href="matakuliah_index.php">Kelola Mata kuliah</a>
            <a href="materi_index.php">Kelola Materi</a>
            <a href="tugas_index.php">Kelola Tugas</a>
        </div>
    </div>
</body>
</html>