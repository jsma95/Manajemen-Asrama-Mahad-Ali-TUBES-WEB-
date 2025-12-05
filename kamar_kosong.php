<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

$nama = $_SESSION['nama'];

// Ambil kamar yang masih punya sisa tempat
$query = "SELECT *, (kapasitas - terisi) AS sisa 
          FROM tb_kamar 
          HAVING sisa > 0
          ORDER BY lantai, nama_kamar";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kamar Kosong</title>
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
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="pemesanan.php">Pemesanan</a></li>
        <li><a href="pemesanan_tambah.php">Pesan Kamar</a></li>
        <li><a href="kamar_kosong.php" class="active">Kamar Kosong</a></li>
        <li><a href="info_asrama.php">Informasi Asrama</a></li>
    </ul>

    <div class="navbar-right">
        <span>👤 <?= htmlspecialchars($nama); ?></span>
        <a href="logout.php">
            <button class="navbar-logout-btn">Logout</button>
        </a>
    </div>
</nav>

<main class="page-container">

    <!-- HEADER HALAMAN -->
    <header class="page-header">
        <h1 class="page-title">Kamar yang Masih Tersedia</h1>
        <p class="page-subtitle">
            Daftar kamar asrama yang masih memiliki sisa tempat untuk dihuni.
        </p>
    </header>
    <!-- TABEL KAMAR KOSONG -->
    <section class="table-card">
        <table class="table-asrama">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kamar</th>
                    <th>Lantai</th>
                    <th>Kapasitas</th>
                    <th>Terisi</th>
                    <th>Sisa</th>
                    <th>Harga / Tahun</th>
                    <th>Fasilitas</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($result) === 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center; padding:12px;">
                        Saat ini tidak ada kamar kosong. Silakan cek kembali beberapa waktu lagi
                        atau hubungi pengelola asrama.
                    </td>
                </tr>
            <?php
            else:
                while ($row = mysqli_fetch_assoc($result)) :
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_kamar']); ?></td>
                    <td>Lantai <?= htmlspecialchars($row['lantai']); ?></td>
                    <td><?= (int)$row['kapasitas']; ?> orang</td>
                    <td><?= (int)$row['terisi']; ?> orang</td>
                    <td>
                        <span class="sisa-badge">
                            <?= (int)$row['sisa']; ?> tersedia
                        </span>
                    </td>
                    <td>Rp <?= number_format($row['harga_pertahun'], 0, ',', '.'); ?></td>
                    <td><?= nl2br(htmlspecialchars($row['fasilitas'])); ?></td>
                </tr>
            <?php
                endwhile;
            endif;
            ?>
            </tbody>
        </table>
    </section>

</main>

</body>
</html>
