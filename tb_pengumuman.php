<?php
// KONFIGURASI DATABASE
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_asrama";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// =========================
// 1. BUAT TABEL
// =========================
$sqlCreate = "CREATE TABLE IF NOT EXISTS tb_pengumuman (
    id_pengumuman INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    tanggal_post DATE NOT NULL DEFAULT (CURRENT_DATE),
    dibuat_oleh VARCHAR(100) DEFAULT 'Admin'
);
";

if (mysqli_query($conn, $sqlCreate)) {
    echo "<p><b>Tabel tb_pengumuman berhasil dibuat / sudah ada.</b></p>";
} else {
    die("<p>Error membuat tabel: " . mysqli_error($conn) . "</p>");
}

// =========================
// 2. INSERT DATA AWAL
// =========================

$sqlInsert = "INSERT INTO tb_pengumuman (judul, isi) VALUES
        ('Pemberitahuan Pembayaran Asrama',
        'Pembayaran asrama jatuh tempo hingga tanggal 15 bulan ini. Harap melakukan pembayaran tepat waktu.'),

        ('Kegiatan Pengajian Rutin',
        'Pengajian rutin santri akan diadakan setiap malam Jumat di Aula lantai 2.'),

        ('Jadwal Kebersihan Asrama',
        'Harap seluruh penghuni berpartisipasi dalam piket kebersihan sesuai jadwal yang telah dibagikan.');
        ";

if (mysqli_query($conn, $sqlInsert)) {
    echo "<p><b>Data awal berhasil ditambahkan ke tb_pengumuman.</b></p>";
} else {
    echo "<p><b>Data tidak ditambahkan (mungkin sudah ada):</b> " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);

echo "<p>Selesai.</p>";
?>
