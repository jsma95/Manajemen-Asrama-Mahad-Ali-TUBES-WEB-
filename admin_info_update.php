<?php
session_start();

// opsional: pastikan hanya admin yang boleh update
if (!isset($_SESSION['id_user']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_info = (int)($_POST['id_info'] ?? 0);
    $judul   = trim($_POST['judul'] ?? '');
    $isi     = trim($_POST['isi'] ?? '');

    // validasi sederhana
    if ($id_info <= 0 || $judul === '' || $isi === '') {
        $_SESSION['flash_error'] = "Judul dan isi tidak boleh kosong.";
        header("Location: admin_info_asrama.php");
        exit;
    }

    // escape untuk mencegah SQL injection
    $judul_safe = mysqli_real_escape_string($koneksi, $judul);
    $isi_safe   = mysqli_real_escape_string($koneksi, $isi);

    $sql = "
        UPDATE tb_info_asrama
        SET judul = '$judul_safe',
            isi = '$isi_safe',
            tanggal_update = NOW()
        WHERE id_info = $id_info
        LIMIT 1
    ";

    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['flash_success'] = "Informasi asrama berhasil diperbarui.";
    } else {
        $_SESSION['flash_error'] = "Gagal memperbarui: " . mysqli_error($koneksi);
    }
}

// selalu kembali ke halaman admin_info_asrama
header("Location: admin_info_asrama.php");
exit;
