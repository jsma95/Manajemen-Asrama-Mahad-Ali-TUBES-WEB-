<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

$nama = $_SESSION['nama'];

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
        <li><a href="pemesanan_tambah.php" class="active">Pesan Kamar</a></li>
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

<main class="page-container">

    <!-- HEADER HALAMAN -->
    <header class="page-header">
        <h1 class="page-title">Pesan Kamar Asrama</h1>
        <p class="page-subtitle">
            Silakan pilih kamar yang tersedia dan tambahkan catatan jika diperlukan.
        </p>
    </header>
    <!-- FORM PEMESANAN DALAM CARD -->
    <section class="form-card">
        <form action="pemesanan_insert.php" method="POST" class="asrama-form">

            <div class="form-group">
                <label for="id_kamar" class="form-label">Pilih Kamar</label>
                <select name="id_kamar" id="id_kamar" class="form-select" required>
                    <option value="">-- pilih kamar --</option>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <option value="<?= $row['id_kamar']; ?>">
                            <?= htmlspecialchars($row['nama_kamar']); ?> 
                            (Lantai <?= (int)$row['lantai']; ?>, Sisa: <?= (int)$row['sisa']; ?>, 
                            Harga: Rp <?= number_format($row['harga_pertahun'],0,',','.'); ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
                <p class="form-help">
                    Hanya kamar dengan sisa tempat yang ditampilkan di sini.
                </p>
            </div>

            <div class="form-group">
                <label for="catatan" class="form-label">Catatan (opsional)</label>
                <textarea name="catatan" id="catatan" class="form-textarea" rows="3"
                    placeholder="Contoh: ingin kamar dekat jendela, atau info tambahan lain..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-main">Simpan Pemesanan</button>
                <a href="pemesanan.php" class="btn-secondary-ghost">Batal</a>
            </div>

        </form>
    </section>

</main>

</body>
</html>
