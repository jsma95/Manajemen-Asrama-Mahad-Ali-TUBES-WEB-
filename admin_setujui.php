<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include "config.php";

$id_pemesanan = (int)($_GET['id'] ?? 0);
if ($id_pemesanan <= 0) {
    header("Location: admin_pemesanan.php");
    exit;
}

// Ambil data pemesanan
$sql = "SELECT * FROM tb_pemesanan WHERE id_pemesanan = $id_pemesanan";
$res = mysqli_query($koneksi, $sql);
$p = mysqli_fetch_assoc($res);

if (!$p) {
    header("Location: admin_pemesanan.php");
    exit;
}

$id_user  = (int)$p['id_user'];
$id_kamar = (int)$p['id_kamar'];

// 1) Cek apakah user sudah punya pesanan yang disetujui
$sqlCek = "SELECT COUNT(*) AS jml 
           FROM tb_pemesanan 
           WHERE id_user = $id_user AND status = 'disetujui'";
$resCek = mysqli_query($koneksi, $sqlCek);
$rowCek = mysqli_fetch_assoc($resCek);

if ($rowCek['jml'] > 0) {
    // sudah ada yang disetujui, jangan setujui lagi
    header("Location: admin_pemesanan.php");
    exit;
}

// 2) Cek kapasitas kamar
$sqlKamar = "SELECT kapasitas, terisi FROM tb_kamar WHERE id_kamar = $id_kamar";
$resKamar = mysqli_query($koneksi, $sqlKamar);
$kamar = mysqli_fetch_assoc($resKamar);

if (!$kamar) {
    header("Location: admin_pemesanan.php");
    exit;
}

if ($kamar['terisi'] >= $kamar['kapasitas']) {
    // kamar sudah penuh
    header("Location: admin_pemesanan.php");
    exit;
}

// 3) Set pemesanan ini menjadi DISETUJUI
mysqli_query($koneksi, 
    "UPDATE tb_pemesanan 
     SET status = 'disetujui' 
     WHERE id_pemesanan = $id_pemesanan"
);

// 4) Otomatis batalkan pemesanan lain milik user yang sama
mysqli_query($koneksi,
    "UPDATE tb_pemesanan
     SET status = 'batal'
     WHERE id_user = $id_user 
       AND id_pemesanan <> $id_pemesanan"
);

// 5) Tambah terisi kamar
mysqli_query($koneksi,
    "UPDATE tb_kamar 
     SET terisi = terisi + 1 
     WHERE id_kamar = $id_kamar"
);

header("Location: admin_pemesanan.php");
exit;
