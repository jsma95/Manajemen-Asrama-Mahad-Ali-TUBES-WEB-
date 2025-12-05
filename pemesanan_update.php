<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

$id_user       = $_SESSION['id_user'];
$id_pemesanan  = $_POST['id_pemesanan'];
$id_kamar_baru = $_POST['id_kamar'];
$catatan       = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');

// Cek pemesanan lama
$sql = "SELECT * FROM tb_pemesanan 
        WHERE id_pemesanan=$id_pemesanan AND id_user=$id_user LIMIT 1";
$res = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($res);

if (!$data || $data['status'] != 'aktif') {
    die("Pemesanan tidak valid.");
}

$id_kamar_lama = $data['id_kamar'];

// Update pemesanan
mysqli_query($koneksi, 
    "UPDATE tb_pemesanan 
     SET id_kamar=$id_kamar_baru, catatan='$catatan' 
     WHERE id_pemesanan=$id_pemesanan");

// Update kapasitas kamar (kalau kamar diganti)
if ($id_kamar_baru != $id_kamar_lama) {
    mysqli_query($koneksi, 
        "UPDATE tb_kamar SET terisi = terisi - 1 WHERE id_kamar = $id_kamar_lama");
    mysqli_query($koneksi, 
        "UPDATE tb_kamar SET terisi = terisi + 1 WHERE id_kamar = $id_kamar_baru");
}

header("Location: pemesanan.php");
exit;
