<?php
$host = "localhost";
$user = "root";
$pass = "290607";
$db   = "skema_lms";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>