<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

$id_user       = (int)$_SESSION['id_user'];
$id_pemesanan  = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_pemesanan <= 0) {
    header("Location: pemesanan.php");
    exit;
}

// 1. Ambil data pemesanan yang mau dibatalkan (punya user ini & masih aktif)
$sql = "SELECT id_pemesanan, id_kamar
        FROM tb_pemesanan
        WHERE id_pemesanan = $id_pemesanan
          AND id_user = $id_user
          AND status = 'aktif'
        LIMIT 1";
$res = mysqli_query($koneksi, $sql);
$pemesanan = mysqli_fetch_assoc($res);

if (!$pemesanan) {
    // tidak ditemukan atau bukan milik user atau sudah batal
    header("Location: pemesanan.php");
    exit;
}

$id_kamar = (int)$pemesanan['id_kamar'];

// 2. Ubah status pemesanan menjadi batal
mysqli_query($koneksi, "
    UPDATE tb_pemesanan
    SET status = 'batal'
    WHERE id_pemesanan = $id_pemesanan
");

// 3. Kurangi terisi di kamar (jangan sampai minus)
mysqli_query($koneksi, "
    UPDATE tb_kamar
    SET terisi = GREATEST(terisi - 1, 0)
    WHERE id_kamar = $id_kamar
");

// 4. Kembali ke halaman pemesanan
header("Location: pemesanan.php");
exit;
