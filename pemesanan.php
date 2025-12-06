<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

$id_user = (int)$_SESSION['id_user'];
$username = $_SESSION['username'] ?? '';

// Ambil semua pemesanan milik user ini
$sql = "SELECT p.*, k.nama_kamar, k.harga_pertahun 
        FROM tb_pemesanan p
        JOIN tb_kamar k ON p.id_kamar = k.id_kamar
        WHERE p.id_user = $id_user
        ORDER BY p.tanggal_pesan DESC";
$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Kamar Asrama</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR SAMA DENGAN DASHBOARD -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="dashboard.php" class="navbar-brand">
            Ma'had Aly UINAM
        </a>
    </div>

    <ul class="navbar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="pemesanan.php" class="active">Pemesanan</a></li>
        <li><a href="kamar_kosong.php">Kamar Kosong</a></li>
        <li><a href="info_asrama.php">Informasi Asrama</a></li>
    </ul>

   <div class="navbar-right">
        <span>👤 <?= $username !== '' ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : 'Guest'; ?></span>
        <a href="logout.php">
            <button class="navbar-logout-btn">Logout</button>
        </a>
    </div>
</nav>

<main class="page-container">

    <!-- HEADER HALAMAN -->
    <header class="page-header">
        <h1 class="page-title">Pemesanan Kamar Asrama</h1>
        <p class="page-subtitle">
            Riwayat pemesanan kamar yang pernah Anda ajukan di asrama Ma'had Aly UINAM.
        </p>
    </header>

    <!-- TOMBOL AKSI -->
    <div class="action-bar">
        <a href="pemesanan_tambah.php" class="btn-main">+ Pesan Kamar</a>
    </div>

    <!-- TABEL DALAM KARTU -->
    <section class="table-card">
        <table class="table-asrama">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kamar</th>
                    <th>Tanggal Pesan</th>
                    <th>Harga / Tahun</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($result) === 0): ?>
                <tr>
                    <td colspan="7" style="text-align:center; padding:12px;">
                        Anda belum memiliki pemesanan kamar. Silakan klik 
                        <strong>Pesan Kamar</strong> untuk membuat pemesanan baru.
                    </td>
                </tr>
            <?php
            else:
                while ($row = mysqli_fetch_assoc($result)) :
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_kamar']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pesan']); ?></td>
                    <td>Rp <?= number_format($row['harga_pertahun'], 0, ',', '.'); ?></td>
                    <td>
                        <?php if ($row['status'] == 'aktif'): ?>
                            <span class="status-badge status-aktif">Aktif</span>
                            <!-- atau kalau mau tulisannya "Disetujui" -->
                            <!-- <span class="status-badge status-aktif">Disetujui</span> -->

                        <?php elseif ($row['status'] == 'batal'): ?>
                            <span class="status-badge status-batal">Batal</span>
                        <?php else: ?>
                            <span class="status-badge">Menunggu</span>
                        <?php endif; ?>
                    </td>
                    <td><?= nl2br(htmlspecialchars($row['catatan'])); ?></td>
                    <td>
                         <?php if ($row['status'] == 'aktif'): ?>
                            <!-- kalau sudah disetujui admin -->
                            <span style="color:#0b6b43;font-weight:600;">Disetujui admin</span>
                        <?php elseif ($row['status'] == 'batal'): ?>
                            <span style="color:#999;">Tidak ada aksi</span>
                        <?php else: ?>
                            <!-- status lain (kalau kamu punya), misal menunggu, boleh Edit/Batal -->
                            <a href="pemesanan_edit.php?id=<?= $row['id_pemesanan']; ?>">
                                <button class="table-btn edit">Edit</button>
                            </a>
                            <a href="pemesanan_batal.php?id=<?= $row['id_pemesanan']; ?>">
                                <button class="table-btn batal">Batalkan</button>
                            </a>
                        <?php endif; ?>
                    </td>
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
