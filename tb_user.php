<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_asrama";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if(mysqli_connect_errno()){
    die("Koneksi gagal: ".mysqli_connect_error());
}

// 1. Buat tabel
$sqlCreate = "CREATE TABLE IF NOT EXISTS tb_user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
)";

if (!mysqli_query($conn, $sqlCreate)) {
    die("Gagal membuat tabel tb_user: " . mysqli_error($conn));
}

// 2. Insert data
$sqlInsert = "INSERT INTO tb_user (username, password, role) VALUES
    ('Fadillah', '60900123073', 'user'),
    ('NurAisyah', '60900123053', 'user'),
    ('Rasnah', '60900123066', 'user'),
    ('Hikma Ramadani', '60900123012', 'user'),
    ('Andi Amaliah', '60900123051', 'user'),
    ('Sayyid Rafsanjani', '60900123057', 'user'),
    ('Arya Kaisra', '60900123061', 'user'),
    ('Afdal', '60900123056', 'user'),
    ('Administrator', 'admin123', 'admin')
";
$sqlInsert = "INSERT INTO tb_user (username, password, role) VALUES
    ('Jusmawati', '60900123014', 'user')
";

if (!mysqli_query($conn, $sqlInsert)) {
    die("Gagal insert data user: " . mysqli_error($conn));
}

echo "Tabel & data tb_user berhasil dibuat!";
mysqli_close($conn);
?>
