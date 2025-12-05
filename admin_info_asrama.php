<?php
session_start();

// cek harus admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include "config.php";

// kalau belum ada baris id=1, buat default
$cek = mysqli_query($koneksi, "SELECT * FROM tb_info_asrama WHERE id = 1");
if (mysqli_num_rows($cek) == 0) {
    mysqli_query($koneksi, "INSERT INTO tb_info_asrama (id, deskripsi)
                            VALUES (1, 'Asrama Ma''had Aly UINAM')");
}

// proses update
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi'] ?? '');
    $update = "UPDATE tb_info_asrama SET deskripsi = '$deskripsi' WHERE id = 1";
    if (mysqli_query($koneksi, $update)) {
        $success = "Informasi asrama berhasil diperbarui.";
    } else {
        $success = "Gagal menyimpan: " . mysqli_error($koneksi);
    }
}

// ambil data terbaru
$res = mysqli_query($koneksi, "SELECT deskripsi FROM tb_info_asrama WHERE id = 1");
$data = mysqli_fetch_assoc($res);
$deskripsi_now = $data ? $data['deskripsi'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Info Asrama</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        <span class="navbar-brand">Admin Asrama</span>
    </div>
    <ul class="navbar-menu">
        <li><a href="admin_pemesanan.php">Pemesanan</a></li>
        <li><a href="admin_info_asrama.php" class="active">Info Asrama</a></li>
        <li><a href="admin_pengumuman.php">Pengumuman</a></li>
    </ul>
    <div class="navbar-right">
        <span>👤 (Admin)</span>
        <a href="logout.php"><button class="navbar-logout-btn">Logout</button></a>
    </div>
</nav>

<main class="page-container">
    <header class="page-header">
        <h1 class="page-title">Kelola Informasi Asrama</h1>
        <p class="page-subtitle">Update deskripsi singkat mengenai asrama Ma'had Aly UINAM.</p>
    </header>

    <section class="table-card">
        <?php if (!empty($success)) : ?>
            <div style="margin-bottom:10px; padding:8px 10px; border-radius:6px; background:#e6f7ef; color:#064f3b; font-size:13px;">
                <?= htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <label for="deskripsi" style="font-weight:600; font-size:14px;">Deskripsi Asrama</label>
            <textarea name="deskripsi" id="deskripsi" rows="8"
                      style="width:100%; margin-top:6px; padding:10px; font-size:14px; border-radius:8px; border:1px solid #ccc;"><?= htmlspecialchars($deskripsi_now); ?></textarea>

            <div style="margin-top:12px;">
                <button type="submit" class="btn-main">Simpan Perubahan</button>
            </div>
        </form>
    </section>
</main>

</body>
</html>
