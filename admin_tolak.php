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

// Opsional: kalau kamu ingin mengurangi terisi kamar ketika membatalkan
$sql = "SELECT id_kamar, status FROM tb_pemesanan WHERE id_pemesanan = $id_pemesanan";
$res = mysqli_query($koneksi, $sql);
$p = mysqli_fetch_assoc($res);

if ($p && $p['status'] === 'disetujui') {
    $id_kamar = (int)$p['id_kamar'];
    mysqli_query($koneksi,
        "UPDATE tb_kamar 
         SET terisi = GREATEST(terisi - 1, 0)
         WHERE id_kamar = $id_kamar"
    );
}

// ubah status jadi batal
mysqli_query($koneksi,
    "UPDATE tb_pemesanan 
     SET status = 'batal' 
     WHERE id_pemesanan = $id_pemesanan"
);

header("Location: admin_pemesanan.php");
exit;
