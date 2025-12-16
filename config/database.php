<?php

$host = "localhost";
$port = "5433";
$dbname = "lab_ai";
$user = "postgres";
<<<<<<< HEAD
$password = "12345678"; 
=======
$password = "admin123"; 
>>>>>>> 6a1f2d804e127f89ed2bcf8f9092bf4e8293cdb7

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Koneksi ke database gagal: " . pg_last_error());
}
?>
