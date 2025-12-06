<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

$id_user       = (int) $_SESSION['id_user'];
$username      = $_SESSION['username'] ?? '';
$id_pemesanan  = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil pemesanan milik user ini
$sql = "SELECT * FROM tb_pemesanan 
        WHERE id_pemesanan = $id_pemesanan 
          AND id_user = $id_user 
        LIMIT 1";
$res  = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($res);

if (!$data || $data['status'] != 'aktif') {
    die("Pemesanan tidak ditemukan atau sudah tidak bisa diubah.");
}

// Ambil semua kamar (nanti kita hitung sisa di PHP)
$sqlKamar  = "SELECT k.*, (k.kapasitas - k.terisi) AS sisa FROM tb_kamar k";
$kamarRes  = mysqli_query($koneksi, $sqlKamar);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pemesanan Kamar</title>
    <link rel="stylesheet" href="style.css?v=5">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="dashboard.php" class="navbar-brand">Ma'had Aly UINAM</a>
    </div>

    <ul class="navbar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="pemesanan.php" class="active">Pemesanan</a></li>
        <li><a href="pemesanan_tambah.php">Pesan Kamar</a></li>
        <li><a href="kamar_kosong.php">Kamar Kosong</a></li>
        <li><a href="info_asrama.php">Informasi Asrama</a></li>
    </ul>

    <div class="navbar-right">
        <span>👤 <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>
        <a href="logout.php">
            <button class="navbar-logout-btn">Logout</button>
        </a>
    </div>
</nav>

<main class="page-container">

    <section class="form-section">
        <header class="page-header">
            <h1 class="page-title">Edit Pemesanan Kamar</h1>
            <p class="page-subtitle">
                Ubah pilihan kamar atau catatan pemesanan Anda, selama status masih aktif.
            </p>
        </header>

        <a href="pemesanan.php" class="back-link">← Kembali ke daftar pemesanan</a>

        <form action="pemesanan_update.php" method="POST" class="form-card">
            <input type="hidden" name="id_pemesanan" value="<?= $data['id_pemesanan']; ?>">

            <!-- PILIH KAMAR -->
            <div class="form-group">
                <label for="id_kamar" class="form-label">Pilih Kamar</label>
                <select name="id_kamar" id="id_kamar" class="form-input" required>
                    <?php while ($row = mysqli_fetch_assoc($kamarRes)) : 
                        $sisa     = $row['kapasitas'] - $row['terisi'];
                        $selected = $row['id_kamar'] == $data['id_kamar'] ? 'selected' : '';

                        // Kalau sisa 0 dan bukan kamar yang sedang dipakai, kita disable
                        $disabled_text = ($sisa <= 0 && !$selected) ? ' (penuh)' : " (Sisa: $sisa)";
                        $disabled_attr = ($sisa <= 0 && !$selected) ? 'disabled' : '';
                    ?>
                        <option
                            value="<?= $row['id_kamar']; ?>"
                            <?= $selected; ?> <?= $disabled_attr; ?>
                        >
                            <?= htmlspecialchars($row['nama_kamar']); ?>
                            (Lantai <?= $row['lantai']; ?><?= $disabled_text; ?>,
                            Rp <?= number_format($row['harga_pertahun'], 0, ',', '.'); ?>/tahun)
                        </option>
                    <?php endwhile; ?>
                </select>
                <p class="help-text">
                    Kamar yang sudah penuh akan ditandai <strong>(penuh)</strong> dan tidak bisa dipilih.
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
                    placeholder="Contoh: tetap ingin dekat jendela, dsb..."
                ><?= htmlspecialchars($data['catatan']); ?></textarea>
            </div>

            <!-- TOMBOL -->
            <div class="form-actions">
                <button type="submit" class="btn-main">Update Pemesanan</button>
                <a href="pemesanan.php" class="btn-secondary-ghost form-cancel-link">
                    Batal
                </a>
            </div>
        </form>
    </section>

</main>

</body>
</html>
