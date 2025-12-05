<?php
session_start();

// hanya admin yang boleh masuk
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include "config.php"; // pastikan variabelnya $koneksi

// AMBIL SEMUA PEMESANAN DENGAN NAMA USER & NAMA KAMAR
$sql = "SELECT 
            p.id_pemesanan,
            p.id_user,
            u.username AS nama_pengguna,
            k.nama_kamar,
            p.tanggal_pesan,
            p.status,
            p.catatan
        FROM tb_pemesanan p
        JOIN tb_user   u ON p.id_user  = u.id_user
        JOIN tb_kamar  k ON p.id_kamar = k.id_kamar
        ORDER BY p.tanggal_pesan DESC";

$result = mysqli_query($koneksi, $sql);

// CEK KALAU QUERY-NYA ERROR
if (!$result) {
    die("Query pemesanan gagal: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Pemesanan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        <span class="navbar-brand">Admin Asrama</span>
    </div>
    <ul class="navbar-menu">
        <li><a href="admin_pemesanan.php" class="active">Pemesanan</a></li>
        <li><a href="admin_info_asrama.php">Info Asrama</a></li>
        <li><a href="admin_pengumuman.php">Pengumuman</a></li>
    </ul>
    <div class="navbar-right">
        <span>👤 (Admin)</span>
        <a href="logout.php"><button class="navbar-logout-btn">Logout</button></a>
    </div>
</nav>

<main class="page-container">
    <header class="page-header">
        <h1 class="page-title">Kelola Pemesanan Kamar</h1>
        <p class="page-subtitle">Setujui atau tolak permintaan kamar dari penghuni.</p>
    </header>

    <section class="table-card">
        <table class="table-asrama">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pengguna</th>
                    <th>Nama Kamar</th>
                    <th>Tanggal Pesan</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) :
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_pengguna']); ?></td>
                    <td><?= htmlspecialchars($row['nama_kamar']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pesan']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td><?= nl2br(htmlspecialchars($row['catatan'])); ?></td>
                    <td>
                        <?php if ($row['status'] !== 'batal') : ?>
                            <!-- Tombol SETUJUI -->
                            <a href="admin_setujui.php?id=<?= $row['id_pemesanan']; ?>"
                            onclick="return confirm('Setujui pemesanan ini? Pengguna hanya boleh punya 1 pesanan disetujui.');">
                                <button class="table-btn edit">Setujui</button>
                            </a>

                            <!-- Tombol BATALKAN -->
                            <a href="admin_tolak.php?id=<?= $row['id_pemesanan']; ?>"
                            onclick="return confirm('Batalkan pemesanan ini?');">
                                <button class="table-btn batal">Batalkan</button>
                            </a>
                        <?php else: ?>
        <small>Tidak ada aksi</small>
    <?php endif; ?>
</td>


                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
