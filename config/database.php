<?php
// config/database.php
$host = "localhost";
$port = "5432";
$dbname = "lab_ai";
$user = "postgres";

$password = "12345678"; // ubah sesuai konfigurasi kamu

$password = "12345678";


$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Koneksi ke database gagal: " . pg_last_error());
}
?>
