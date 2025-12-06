<?php
session_start();

// cek admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include "config.php";

$info = '';

// hapus pengumuman
if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM tb_pengumuman WHERE id = $id_hapus");
}

// tambah pengumuman baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul'] ?? '');
    $isi   = mysqli_real_escape_string($koneksi, $_POST['isi'] ?? '');
    $tgl   = date('Y-m-d');

    if ($judul !== '' && $isi !== '') {
        $insert = "INSERT INTO tb_pengumuman (judul, isi, tanggal_post)
                   VALUES ('$judul', '$isi', '$tgl')";
        if (mysqli_query($koneksi, $insert)) {
            $info = "Pengumuman baru berhasil ditambahkan.";
        } else {
            $info = "Gagal menambah pengumuman: " . mysqli_error($koneksi);
        }
    } else {
        $info = "Judul dan isi pengumuman wajib diisi.";
    }
}

// ambil semua pengumuman
$res = mysqli_query($koneksi, "SELECT * FROM tb_pengumuman ORDER BY tanggal_post DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Pengumuman Asrama</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        <span class="navbar-brand">Admin Asrama</span>
    </div>
    <ul class="navbar-menu">
        <li><a href="admin_pemesanan.php">Pemesanan</a></li>
        <li><a href="admin_info_asrama.php">Info Asrama</a></li>
        <li><a href="admin_pengumuman.php" class="active">Pengumuman</a></li>
    </ul>
    <div class="navbar-right">
        <span>Hii, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
        <a href="logout.php"><button class="navbar-logout-btn">Logout</button></a>
    </div>
</nav>

<main class="page-container">
    <header class="page-header">
        <h1 class="page-title">Kelola Pengumuman Asrama</h1>
        <p class="page-subtitle">Tambah atau hapus pengumuman untuk penghuni asrama.</p>
    </header>

    <!-- Form tambah pengumuman -->
    <section class="table-card" style="margin-bottom:24px;">
        <?php if (!empty($info)) : ?>
            <div style="margin-bottom:10px; padding:8px 10px; border-radius:6px; background:#e6f7ef; color:#064f3b; font-size:13px;">
                <?= htmlspecialchars($info); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label style="font-weight:600; font-size:14px;">Judul Pengumuman</label>
                <input type="text" name="judul" class="form-control"
                       style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #ccc; margin-top:4px;">
            </div>
            <div class="mb-3" style="margin-top:10px;">
                <label style="font-weight:600; font-size:14px;">Isi Pengumuman</label>
                <textarea name="isi" rows="4"
                          style="width:100%; margin-top:4px; padding:8px 10px; border-radius:8px; border:1px solid #ccc;"></textarea>
            </div>
            <button type="submit" class="btn-main">Tambah Pengumuman</button>
        </form>
    </section>

    <!-- Daftar pengumuman -->
   
</main>

</body>
</html>
