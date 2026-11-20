<?php

$host = "localhost";
$dbname = "db_kopi_member";
$username = "root";
$password = "";

$konfigurasi = "mysql:host=$host;dbname=db_kopi_member;";



try {
    $koneksi = new PDO ($konfigurasi, $username, $password);
} catch (PDOException $e) {
    die("Gagal terhubung ke database " . $e->getMessage());
}

?>