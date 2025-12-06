<?php
session_start();
include "config.php";

// Cek role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak.");
}

// Ambil id_info dari URL kalau ada
$edit_id = isset($_GET['id_info']) ? (int)$_GET['id_info'] : 0;

// Jika ada id_info yang dipilih, ambil datanya
$info = null;
if ($edit_id > 0) {
    $sqlDetail = "SELECT * FROM tb_info_asrama WHERE id_info = $edit_id";
    $resDetail = mysqli_query($koneksi, $sqlDetail);
    $info = mysqli_fetch_assoc($resDetail);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Informasi Asrama</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        <a href="admin_pemesanan.php" class="navbar-brand">Admin Asrama</a>
    </div>

    <ul class="navbar-menu">
        <li><a href="admin_pemesanan.php">Pemesanan</a></li>
        <li><a href="admin_info_asrama.php" class="active">Info Asrama</a></li>
        <li><a href="admin_pengumuman.php">Pengumuman</a></li>
    </ul>

    <div class="navbar-right">
        <span>Hii, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
        <a href="logout.php">
            <button class="navbar-logout-btn">Logout</button>
        </a>
    </div>
</nav>

<main class="page-container">

    <h1 class="page-title">Kelola Informasi Asrama</h1>
    <p class="page-subtitle">
        Edit informasi umum asrama, fasilitas, dan aturan penghuni.
    </p>

    <!-- DAFTAR INFO -->
    <section class="info-section">
        <h2 class="info-subtitle">Daftar Informasi</h2>

        <div class="info-table-wrapper">
            <table class="info-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // PENTING: tidak ada lagi WHERE id = ...
                $sqlList = "SELECT * FROM tb_info_asrama ORDER BY id_info ASC";
                $resList = mysqli_query($koneksi, $sqlList);

                while ($row = mysqli_fetch_assoc($resList)) :
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['judul']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_update']); ?></td>
                        <td>
                            <a href="admin_info_asrama.php?id_info=<?= $row['id_info']; ?>">
                                <button class="table-btn edit">Edit</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- FORM EDIT INFO -->
    <?php if ($info): ?>
    <section class="form-section">
        <h2 class="info-subtitle">Edit Informasi: <?= htmlspecialchars($info['judul']); ?></h2>

        <form action="admin_info_update.php" method="POST" class="form-card">
            <input type="hidden" name="id_info" value="<?= $info['id_info']; ?>">

            <div class="form-group">
                <label class="form-label">Judul</label>
                <input
                    type="text"
                    name="judul"
                    class="form-input"
                    required
                    value="<?= htmlspecialchars($info['judul']); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Isi Informasi</label>
                <textarea
                    name="isi"
                    class="form-textarea"
                    rows="6"
                    required><?= htmlspecialchars($info['isi']); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-main">Simpan Perubahan</button>
                <a href="admin_info_asrama.php" class="btn-secondary-ghost">Batal</a>
            </div>
        </form>
    </section>
    <?php endif; ?>

</main>

</body>
</html>
