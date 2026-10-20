<?php
require 'koneksi.php';

function hitungData($conn, $tabel) {
    $stmt = $conn->prepare("SELECT COUNT(id) as total FROM $tabel");
    $stmt->execute();
    $hasil = $stmt->get_result()->fetch_assoc();
    return $hasil['total'];
}

$jml_mk = hitungData($conn, 'mata_kuliah');
$jml_materi = hitungData($conn, 'materi');
$jml_tugas = hitungData($conn, 'tugas');
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
            <div class="card">
                <h2><?= $jml_mk ?></h2>
                <p>Mata kuliah</p>
            </div>
            <div class="card">
                <h2><?= $jml_materi ?></h2>
                <p>Materi</p>
            </div>
            <div class="card">
                <h2><?= $jml_tugas ?></h2>
                <p>Tugas</p>
            </div>
        </div>

        <p>Mata kuliah tersedia</p>
        <ul>
            <li>ALP101 - Algoritma dan Pemrograman</li>
            <li>PAW101 - Pengembangan Aplikasi Website</li>
        </ul>

        <div class="menu">
            <a href="mata_kuliah.php">Kelola Mata kuliah</a>
            <a href="materi.php">Kelola Materi</a>
            <a href="tugas.php">Kelola Tugas</a>
        </div>
    </div>
</body>
</html>