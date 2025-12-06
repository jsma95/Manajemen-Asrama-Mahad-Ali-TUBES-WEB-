<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

$id_user  = (int) $_SESSION['id_user'];
$username = $_SESSION['username'] ?? '';

// Ambil kamar yang masih punya sisa kapasitas
$sql = "SELECT k.*, (k.kapasitas - k.terisi) AS sisa 
        FROM tb_kamar k
        HAVING sisa > 0
        ORDER BY lantai, nama_kamar";
$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Kamar Asrama</title>

    <!-- PENTING: HANYA style.css, JANGAN PAKAI BOOTSTRAP DI SINI -->
    <link rel="stylesheet" href="style.css?v=5">
<body>

<nav class="navbar">
    <div class="navbar-left">
        <a href="dashboard.php" class="navbar-brand">Ma'had Aly UINAM</a>
    </div>

    <ul class="navbar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="pemesanan.php">Pemesanan</a></li>
        <li><a href="pemesanan_tambah.php" class="active">Pesan Kamar</a></li>
        <li><a href="kamar_kosong.php">Kamar Kosong</a></li>
        <li><a href="info_asrama.php">Informasi Asrama</a></li>
    </ul>

    <div class="navbar-right">
        <span>👤 <?= htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
        <a href="logout.php">
            <button class="navbar-logout-btn">Logout</button>
        </a>
    </div>
</nav>

<main class="page-container">

    <section class="form-section">
        <header class="page-header">
            <h1 class="page-title">Pesan Kamar Asrama</h1>
            <p class="page-subtitle">
                Silakan pilih kamar yang tersedia dan tambahkan catatan bila diperlukan.
            </p>
        </header>

        <form action="pemesanan_insert.php" method="POST" class="form-card">
            <!-- PILIH KAMAR -->
            <div class="form-group">
                <label for="id_kamar" class="form-label">Pilih Kamar</label>
                <select name="id_kamar" id="id_kamar" class="form-input" required>
                    <option value="">-- pilih kamar --</option>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <option value="<?= $row['id_kamar']; ?>">
                            <?= htmlspecialchars($row['nama_kamar']); ?>
                            (Lantai <?= $row['lantai']; ?>, Sisa: <?= $row['sisa']; ?>,
                            Rp <?= number_format($row['harga_pertahun'], 0, ',', '.'); ?>/tahun)
                        </option>
                    <?php endwhile; ?>
                </select>
                <p class="help-text">
                    Hanya kamar dengan sisa tempat yang ditampilkan di sini.
                </p>
            </div>

            <!-- CATATAN -->
            <div class="form-group">
                <label for="catatan" class="form-label">
                    Catatan <span class="label-optional">(opsional)</span>
                </label>
                <textarea
                    name="catatan"
                    id="catatan"
                    rows="3"
                    class="form-textarea"
                    placeholder="Contoh: ingin kamar dekat jendela, atau info tambahan lain..."
                ></textarea>
            </div>

            <!-- TOMBOL -->
            <div class="form-actions">
                <button type="submit" class="btn-main">Simpan Pemesanan</button>
                <a href="pemesanan.php" class="btn-secondary-ghost form-cancel-link">
                    Batal
                </a>
            </div>
        </form>
    </section>

</main>

</body>
</html>
