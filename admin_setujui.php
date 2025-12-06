<?php
session_start();

// Hanya admin yang boleh akses
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include "config.php";

// Ambil id_pemesanan dari URL, pastikan integer
$id_pemesanan = (int)($_GET['id'] ?? 0);
if ($id_pemesanan <= 0) {
    header("Location: admin_pemesanan.php");
    exit;
}

// 1) Ambil data pemesanan yang akan disetujui
$sql  = "SELECT * FROM tb_pemesanan WHERE id_pemesanan = $id_pemesanan";
$res  = mysqli_query($koneksi, $sql);

if (!$res) {
    die("Query pemesanan gagal: " . mysqli_error($koneksi));
}

$p = mysqli_fetch_assoc($res);

if (!$p) {
    // pemesanan tidak ditemukan
    header("Location: admin_pemesanan.php");
    exit;
}

$id_user  = (int)$p['id_user'];
$id_kamar = (int)$p['id_kamar'];

// 2) Cek apakah user sudah punya pesanan yang AKTIF
//    (di tabel: status ENUM('aktif','batal'))
$sqlCek = "SELECT COUNT(*) AS jml 
           FROM tb_pemesanan 
           WHERE id_user = $id_user 
             AND status = 'aktif'";
$resCek = mysqli_query($koneksi, $sqlCek);

if (!$resCek) {
    die("Query cek pemesanan user gagal: " . mysqli_error($koneksi));
}

$rowCek = mysqli_fetch_assoc($resCek);

if ($rowCek['jml'] > 0) {
    // sudah ada pesanan aktif, jangan setujui lagi
    header("Location: admin_pemesanan.php");
    exit;
}

// 3) Cek kapasitas kamar
$sqlKamar  = "SELECT kapasitas, terisi FROM tb_kamar WHERE id_kamar = $id_kamar";
$resKamar  = mysqli_query($koneksi, $sqlKamar);

if (!$resKamar) {
    die("Query kamar gagal: " . mysqli_error($koneksi));
}

$kamar = mysqli_fetch_assoc($resKamar);

if (!$kamar) {
    // kamar tidak ditemukan
    header("Location: admin_pemesanan.php");
    exit;
}

// Kalau kamar sudah penuh, tidak boleh disetujui
if ((int)$kamar['terisi'] >= (int)$kamar['kapasitas']) {
    header("Location: admin_pemesanan.php");
    exit;
}

// 4) Set pemesanan ini menjadi AKTIF
$q1 = mysqli_query(
    $koneksi,
    "UPDATE tb_pemesanan 
     SET status = 'aktif' 
     WHERE id_pemesanan = $id_pemesanan"
);

if (!$q1) {
    die("Gagal mengubah status pemesanan jadi aktif: " . mysqli_error($koneksi));
}

// 5) Batalkan pemesanan lain milik user yang sama
$q2 = mysqli_query(
    $koneksi,
    "UPDATE tb_pemesanan
     SET status = 'batal'
     WHERE id_user = $id_user 
       AND id_pemesanan <> $id_pemesanan"
);

if (!$q2) {
    die("Gagal membatalkan pemesanan lain: " . mysqli_error($koneksi));
}

// 6) Tambah jumlah terisi kamar
$q3 = mysqli_query(
    $koneksi,
    "UPDATE tb_kamar 
     SET terisi = terisi + 1 
     WHERE id_kamar = $id_kamar"
);

if (!$q3) {
    die("Gagal mengupdate jumlah terisi kamar: " . mysqli_error($koneksi));
}

// Selesai, kembali ke halaman admin pemesanan
header("Location: admin_pemesanan.php");
exit;
