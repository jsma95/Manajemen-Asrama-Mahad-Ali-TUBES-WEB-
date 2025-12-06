<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

$username = $_SESSION['username'] ?? '';

// ambil semua info asrama dari tabel
$sql_info   = "SELECT * FROM tb_info_asrama ORDER BY id_info ASC";
$result_info = mysqli_query($koneksi, $sql_info);

// ambil daftar harga kamar
$sql_kamar = "SELECT nama_kamar, harga_pertahun, fasilitas 
              FROM tb_kamar 
              ORDER BY harga_pertahun ASC";
$result_kamar = mysqli_query($koneksi, $sql_kamar);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Asrama Ma'had Aly UINAM</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- NAVBAR SAMA STYLE DENGAN HALAMAN LAIN -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="dashboard.php" class="navbar-brand">
            Ma'had Aly UINAM
        </a>
    </div>

    <ul class="navbar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="pemesanan.php">Pemesanan</a></li>
        <li><a href="kamar_kosong.php">Kamar Kosong</a></li>
        <li><a href="info_asrama.php" class="active">Informasi Asrama</a></li>
    </ul>

    <div class="navbar-right">
        <span>Hii, <?= $username !== '' ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : 'Guest'; ?></span>
        <a href="logout.php">
            <button class="navbar-logout-btn">Logout</button>
        </a>
    </div>
</nav>

<main class="page-container">

    <!-- HEADER HALAMAN -->
    <header class="page-header">
        <h1 class="page-title">Informasi Asrama Ma'had Aly UINAM</h1>
        <p class="page-subtitle">
            Ringkasan fasilitas, harga kamar, dan aturan tinggal di asrama.
        </p>
    </header>

    <!-- ====== INFORMASI ASRAMA DINAMIS DARI tb_info_asrama ====== -->
    <?php while ($info = mysqli_fetch_assoc($result_info)) : ?>
        <section class="info-section">
            <h2 class="info-title">
                <?= htmlspecialchars($info['judul']); ?>
            </h2>
            <p class="info-text">
                <?= nl2br(htmlspecialchars($info['isi'])); ?>
            </p>
            <?php if (!empty($info['tanggal_update'])) : ?>
                <p style="font-size: 12px; color:#777; margin-top:8px;">
                    Diperbarui: <?= date('d M Y H:i', strtotime($info['tanggal_update'])); ?>
                </p>
            <?php endif; ?>
        </section>
    <?php endwhile; ?>

    <!-- ====== DAFTAR HARGA KAMAR DARI tb_kamar ====== -->
    <section class="info-section">
        <h3 class="info-subtitle">Daftar Harga Kamar Per Tahun</h3>

        <div class="info-table-wrapper">
            <table class="info-table">
                <thead>
                    <tr>
                        <th>Nama Kamar</th>
                        <th>Harga / Tahun</th>
                        <th>Fasilitas Khusus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_kamar)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_kamar']); ?></td>
                            <td>Rp <?= number_format($row['harga_pertahun'], 0, ',', '.'); ?></td>
                            <td><?= nl2br(htmlspecialchars($row['fasilitas'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

</main>
</body>
</html>
