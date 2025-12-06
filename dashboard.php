<?php
session_start();
include "config.php";

// kalau belum login, paksa ke login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user  = (int) $_SESSION['id_user'];
$username = $_SESSION['username'] ?? '';

// --- contoh query statistik sederhana ---
$total_kamar = 0;
$kamar_kosong = 0;
$pemesanan_aktif = 0;

// ambil 3 pengumuman terbaru
$sql_peng = "SELECT * FROM tb_pengumuman 
             ORDER BY tanggal_post DESC 
             LIMIT 3";
$peng_result = mysqli_query($koneksi, $sql_peng);

// total kamar
$q1 = mysqli_query($koneksi, "SELECT COUNT(*) AS jml FROM tb_kamar");
if ($row = mysqli_fetch_assoc($q1)) {
    $total_kamar = (int) $row['jml'];
}

// kamar yang masih tersedia
$q2 = mysqli_query($koneksi,
    "SELECT COUNT(*) AS jml 
     FROM tb_kamar 
     WHERE kapasitas > terisi"
);

if ($row = mysqli_fetch_assoc($q2)) {
    $kamar_kosong = (int) $row['jml'];
}

// pemesanan aktif/disetujui milik user ini
$q3 = mysqli_query($koneksi,
    "SELECT COUNT(*) AS jml 
     FROM tb_pemesanan 
     WHERE id_user = $id_user AND status = 'aktif'"
);
if ($row = mysqli_fetch_assoc($q3)) {
    $pemesanan_aktif = (int) $row['jml'];
}

$status_pemesanan = $pemesanan_aktif > 0 ? "Disetujui" : "Belum Disetujui";
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
        <a href="dashboard.php" class="navbar-brand">Ma'had Aly UINAM</a>
    </div>

    <ul class="navbar-menu">
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="pemesanan.php">Pemesanan</a></li>
        <li><a href="kamar_kosong.php">Kamar Kosong</a></li>
        <li><a href="info_asrama.php">Informasi Asrama</a></li>
    </ul>
    <div class="navbar-right">
        <span>Hii, <?= $username !== '' ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : 'Guest'; ?></span>
        <a href="logout.php">
            <button class="navbar-logout-btn">Logout</button>
        </a>
    </div>
</nav>

<!-- HERO (boleh pakai video seperti yang sudah kamu buat) -->
<section class="hero">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="video_uin.mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <img src="logo_uin.png" class="hero-logo" alt="Logo UIN">
        <h1 class="hero-title">ASRAMA MA'HAD ALY UINAM</h1>
        <p class="hero-subtitle">
            Lingkungan tinggal yang religius, disiplin, dan nyaman untuk studi.<br>
            Tahun Akademik 2024 / 2025
        </p>
    </div>
</section>

<main class="page-container">

    <h2 class="section-title">Akses Cepat</h2>
    <p class="section-subtext">
        Pilih menu berikut untuk mengelola pemesanan kamar dan melihat informasi asrama.
    </p>

    <!-- TIGA KOTAK STATISTIK -->
    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-number"><?= $total_kamar; ?></div>
            <div class="stats-label">Total Kamar Asrama</div>
        </div>
        <div class="stats-card">
            <div class="stats-number"><?= $kamar_kosong; ?></div>
            <div class="stats-label">Kamar Kosong</div>
        </div>
        <div class="stats-card">
            <div class="stats-number"><?= $pemesanan_aktif; ?></div>
            <div class="stats-label">Pemesanan Aktif Anda</div>
        </div>
    </div>

    <!-- PENGUMUMAN SINGKAT -->
    <section class="announcement">
        <h3 class="announcement-title">Pengumuman Asrama</h3>
        <ul class="announcement-list">
        <?php if (mysqli_num_rows($peng_result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($peng_result)): ?>
                <li>
                    <strong><?= htmlspecialchars($row['judul']); ?></strong><br>
                    <?= nl2br(htmlspecialchars($row['isi'])); ?>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>Belum ada pengumuman terbaru.</li>
        <?php endif; ?>
    </ul>
    </section>

    <!-- TOMBOL CEPAT -->
    <div class="important-btns">
        <a href="pemesanan_tambah.php" class="important-btn">Pesan Kamar</a>
        <a href="pemesanan.php" class="important-btn secondary">Lihat Pesanan Saya</a>
    </div>

</main>

</body>
</html>
