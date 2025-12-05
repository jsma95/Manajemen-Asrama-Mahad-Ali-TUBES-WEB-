<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

$id_user = (int)$_SESSION['id_user'];
$id_kamar = isset($_POST['id_kamar']) ? (int)$_POST['id_kamar'] : 0;
$catatan = isset($_POST['catatan']) ? trim($_POST['catatan']) : '';

if ($id_kamar <= 0) {
    // kalau tidak pilih kamar
    header("Location: pemesanan_tambah.php");
    exit;
}

// 1. Cek kamar masih punya sisa kapasitas
$sqlKamar = "SELECT kapasitas, terisi 
             FROM tb_kamar
             WHERE id_kamar = $id_kamar
             LIMIT 1";
$resKamar = mysqli_query($koneksi, $sqlKamar);
$kamar = mysqli_fetch_assoc($resKamar);

if (!$kamar) {
    // kamar tidak ditemukan
    header("Location: pemesanan_tambah.php");
    exit;
}

$sisa = (int)$kamar['kapasitas'] - (int)$kamar['terisi'];
if ($sisa <= 0) {
    // kamar sudah penuh, jangan dipaksa
    // boleh nanti dikasih pesan error pakai session kalau mau
    header("Location: pemesanan_tambah.php");
    exit;
}

// 2. Simpan data pemesanan
$tanggal = date('Y-m-d');  // format YYYY-MM-DD
$status  = 'aktif';

$sqlInsert = "INSERT INTO tb_pemesanan (id_user, id_kamar, tanggal_pesan, status, catatan)
              VALUES ($id_user, $id_kamar, '$tanggal', '$status', '" . mysqli_real_escape_string($koneksi, $catatan) . "')";
mysqli_query($koneksi, $sqlInsert);

// 3. Update kamar → terisi + 1
$sqlUpdateKamar = "UPDATE tb_kamar
                   SET terisi = terisi + 1
                   WHERE id_kamar = $id_kamar";
mysqli_query($koneksi, $sqlUpdateKamar);

// 4. Kembali ke halaman pemesanan
header("Location: pemesanan.php");
exit;
