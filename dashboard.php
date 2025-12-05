<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
$nama = $_SESSION['nama'];
include "config.php";
$id_user = (int)$_SESSION['id_user'];

// TOTAL KAMAR (sesuai jumlah baris di tb_kamar)
$qTotalKamar = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total_kamar
    FROM tb_kamar
");
$totalKamar = mysqli_fetch_assoc($qTotalKamar)['total_kamar'];

// KAMAR KOSONG (kamar yang masih punya sisa tempat)
$qKamarKosong = mysqli_query($koneksi, "
    SELECT COUNT(*) AS kamar_kosong
    FROM tb_kamar
    WHERE kapasitas > terisi
");
$kamarKosong = mysqli_fetch_assoc($qKamarKosong)['kamar_kosong'];

// PEMESANAN AKTIF MILIK USER INI
$qAktifSaya = mysqli_query($koneksi, "
    SELECT COUNT(*) AS aktif_saya
    FROM tb_pemesanan
    WHERE id_user = $id_user
      AND status = 'aktif'
");
$aktifSaya = mysqli_fetch_assoc($qAktifSaya)['aktif_saya'];

// MISAL: JUMLAH PEMESANAN AKTIF SEMUA PENGHUNI (untuk kartu 'Disetujui')
$qAktifSemua = mysqli_query($koneksi, "
    SELECT COUNT(*) AS aktif_semua
    FROM tb_pemesanan
    WHERE status = 'aktif'
");
$aktifSemua = mysqli_fetch_assoc($qAktifSemua)['aktif_semua'];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Asrama</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="dashboard.php" class="navbar-brand">
            Ma'had Aly UINAM
        </a>
    </div>

    <ul class="navbar-menu">
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="kamar_kosong.php">Kamar Kosong</a></li>
        <li><a href="info_asrama.php">Informasi Asrama</a></li>
    </ul>

    <div class="navbar-right">
        <span>👤 <?= htmlspecialchars($nama); ?></span>
        <a href="logout.php">
            <button class="navbar-logout-btn">Logout</button>
        </a>
    </div>
</nav>

<!-- HERO SEDERHANA: LOGO + TEKS DI TENGAH -->
<section class="hero">

    <!-- VIDEO BACKGROUND -->
    <video autoplay muted loop playsinline class="hero-video">
        <source src="video_uin.mp4" type="video/mp4">
    </video>

    <!-- KONTEN DI ATAS VIDEO -->
    <div class="hero-content">
        <img src="logo_uin.png" alt="Logo UIN" class="hero-logo">

        <h1 class="hero-title">ASRAMA MA'HAD ALY UINAM</h1>

        <p class="hero-subtext">
            Lingkungan tinggal yang religius, disiplin, dan nyaman untuk studi.
        </p>

        <p class="hero-year">Tahun Akademik 2024 / 2025</p>

        <a href="kamar_kosong.php" class="hero-btn">
            Lihat Kamar Tersedia
        </a>
    </div>

</section>


<main class="page-container">

    <!-- STATISTIK -->
    <div class="stats-grid">

        <div class="stats-card">
            <div class="stats-number"><?= $totalKamar; ?></div>
            <div class="stats-label">Total Kamar Asrama</div>
        </div>

        <div class="stats-card">
            <div class="stats-number"><?= $kamarKosong; ?></div>
            <div class="stats-label">Kamar Kosong</div>
        </div>

        <div class="stats-card">
            <div class="stats-number"><?= $aktifSaya; ?></div>
            <div class="stats-label">Pemesanan Aktif Anda</div>
        </div>

        <div class="stats-card">
           <div class="stats-number"><?= $aktifSemua; ?></div>
            <div class="stats-label">Status Pemesanan</div>
        </div>

    </div>

    <!-- PENGUMUMAN -->
    <section class="announcement">
        <h2 class="announcement-title">Pengumuman Asrama</h2>
            <p>📌 Penghuni wajib mengikuti aturan yang berlaku</p>
            <p>📌 Pembayaran biaya asrama dilakukan sebelum 15 Februari 2025</p>
            <p>📌 Setiap kamar wajib menjaga kebersihan sebelum jam 10 pagi</p>
    </section>

    <!-- TOMBOL PENTING -->
    <div class="important-btns">
        <a href="pemesanan_tambah.php" class="important-btn">Pesan Kamar</a>
        <a href="pemesanan.php" class="important-btn secondary">Lihat Pesanan Saya</a>
    </div>

</main>


</body>
</html>
