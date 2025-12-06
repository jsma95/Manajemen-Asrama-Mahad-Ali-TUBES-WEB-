<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_asrama";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (mysqli_connect_errno()) {
    echo "Koneksi gagal: " . mysqli_connect_error();
    exit;
}

// 1. Buat tabel kalau belum ada
$sqlCreate = "CREATE TABLE IF NOT EXISTS tb_kamar (
    id_kamar INT AUTO_INCREMENT PRIMARY KEY,
    nama_kamar VARCHAR(50) NOT NULL,
    lantai INT NOT NULL,
    kapasitas INT NOT NULL,
    terisi INT NOT NULL DEFAULT 0,
    harga_pertahun INT NOT NULL,
    fasilitas TEXT NOT NULL
)";
mysqli_query($conn, $sqlCreate);

// 3. Insert data awal
$sqlInsert = "INSERT INTO tb_kamar (nama_kamar, lantai, kapasitas, terisi, harga_pertahun, fasilitas) VALUES
    ('Kamar A1', 1, 4, 2, 3000000, 'Kasur, Lemari, Meja belajar, Wifi, Kamar mandi bersama'),
    ('Kamar A2', 1, 3, 1, 3000000, 'Kasur, Lemari, Meja belajar, Wifi'),
    ('Kamar A3', 1, 2, 2, 3000000, 'Kasur, Lemari, Meja belajar, Kipas angin, Wifi'),
    ('Kamar A4', 1, 4, 3, 3000000, 'Kasur, Lemari, Meja belajar, Wifi, Kamar mandi bersama'),
    ('Kamar A5', 1, 3, 2, 3000000, 'Kasur, Lemari, Meja belajar, Wifi'),

    ('Kamar B1', 2, 3, 2, 3500000, 'Kasur, Lemari, Meja belajar, Wifi, Kamar mandi dalam'),
    ('Kamar B2', 2, 2, 1, 3500000, 'Kasur, Lemari, Meja belajar, AC, Wifi'),
    ('Kamar B3', 2, 3, 3, 3500000, 'Kasur, Lemari, Meja belajar, Wifi'),
    ('Kamar B4', 2, 4, 3, 3500000, 'Kasur, Lemari, Meja belajar, Wifi, Kamar mandi bersama'),
    ('Kamar B5', 2, 2, 2, 3500000, 'Kasur, Lemari, Meja belajar, AC, Wifi'),

    ('Kamar C1', 3, 2, 1, 4000000, 'Kasur, Lemari, Meja belajar, AC, Kamar mandi dalam, Wifi'),
    ('Kamar C2', 3, 3, 2, 4000000, 'Kasur, Lemari, Meja belajar, AC, Wifi'),
    ('Kamar C3', 3, 2, 1, 4000000, 'Kasur, Lemari, Meja belajar, AC, Wifi'),
    ('Kamar C4', 3, 4, 3, 4000000, 'Kasur, Lemari, Meja belajar, Wifi'),
    ('Kamar C5', 3, 3, 2, 4000000, 'Kasur, Lemari, Meja belajar, AC, Wifi'),

    ('Kamar D1', 4, 4, 3, 3000000, 'Kasur, Lemari, Meja belajar, Wifi, Kamar mandi bersama'),
    ('Kamar D2', 4, 3, 1, 3000000, 'Kasur, Lemari, Meja belajar, Wifi'),
    ('Kamar D3', 4, 2, 2, 3000000, 'Kasur, Lemari, Meja belajar, Kipas angin, Wifi'),
    ('Kamar D4', 4, 3, 2, 3000000, 'Kasur, Lemari, Meja belajar, Wifi, Kamar mandi bersama'),
    ('Kamar D5', 4, 2, 1, 3000000, 'Kasur, Lemari, Meja belajar, Wifi'),

    ('Kamar E1', 5, 3, 3, 3500000, 'Kasur, Lemari, Meja belajar, Wifi, Kamar mandi dalam'),
    ('Kamar E2', 5, 2, 1, 3500000, 'Kasur, Lemari, Meja belajar, AC, Wifi'),
    ('Kamar E3', 5, 3, 2, 3500000, 'Kasur, Lemari, Meja belajar, Wifi'),
    ('Kamar E4', 5, 4, 3, 3500000, 'Kasur, Lemari, Meja belajar, Wifi, Kamar mandi bersama'),
    ('Kamar E5', 5, 2, 2, 3500000, 'Kasur, Lemari, Meja belajar, AC, Wifi'),

    ('Kamar F1', 6, 2, 1, 4000000, 'Kasur, Lemari, Meja belajar, AC, Kamar mandi dalam, Wifi'),
    ('Kamar F2', 6, 3, 2, 4000000, 'Kasur, Lemari, Meja belajar, AC, Wifi'),
    ('Kamar F3', 6, 2, 1, 4000000, 'Kasur, Lemari, Meja belajar, Wifi'),
    ('Kamar F4', 6, 4, 3, 4000000, 'Kasur, Lemari, Meja belajar, Wifi'),
    ('Kamar F5', 6, 3, 2, 4000000, 'Kasur, Lemari, Meja belajar, AC, Wifi');";

if (mysqli_query($conn, $sqlInsert)) {
    echo "Data kamar berhasil diisi ulang.";
} else {
    echo "Gagal insert: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
